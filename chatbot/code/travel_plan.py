import networkx as nx
from networkx.classes.function import path_weight
import osmnx as ox
import random
import itertools
import numpy as np
from collections import OrderedDict
from itertools import product
ox.config(use_cache=True, log_console=True)

class TravelPlanner:
    def __init__(self, G, data):
        self.G = G
        self.time_position = None
        data_col = list(data.keys())
        self.points = [[ox.nearest_nodes(self.G, coords[1], coords[0]) for coords in data[i].keys()] for i in data_col]

        self.mapping = {}
        for k,v in zip([i for i in self.points],data_col):
          if type(k) is int:
            temp_map = {i:j for i,j in zip([k], list(data[v].values()))}
          else:
            temp_map = {i:j for i,j in zip(k, list(data[v].values()))}
          self.mapping.update(temp_map)

        if len(self.points)==6:
          self.points[1] = [[i[0],i[1]] for i in list(itertools.permutations(self.points[1],2))]
          self.points[3] = [[i[0],i[1],i[2]] for i in list(itertools.permutations(self.points[3],3))]
        elif len(self.points)==5:
          self.points[0] = [[i[0],i[1]] for i in list(itertools.permutations(self.points[0],2))]
          self.points[2] = [[i[0],i[1],i[2]] for i in list(itertools.permutations(self.points[2],3))]
        elif len(self.points)==4:
          self.points[1] = [[i[0],i[1],i[2]] for i in list(itertools.permutations(self.points[1],3))]
        elif len(self.points)==3:
          self.points[0] = [[i[0],i[1],i[2]] for i in list(itertools.permutations(self.points[0],3))]
        self.cost_dict = {}


    def get_cost_dict(self):
        if type(self.points[0][0]) == int:
            start = [random.choice(self.points[0])]
            self.time_position = False
        else:
            start = self.points[0]
            self.time_position = True
            
        count = 1
        for n in self.points[1:]:
          end = n
          point_lists = [start, end]
          combinations_p = list(product(*point_lists))
          if type(combinations_p[0][1]) != int:
            combinations_p = [[i[0]] + i[1] for i in combinations_p]
            combinations_p = np.array(combinations_p)
            for ix in range(combinations_p.shape[1]-1):
              p2p_path = ox.shortest_path(self.G, combinations_p[:,ix], combinations_p[:,ix+1], weight='length')
              path_cost = np.array([path_weight(self.G, path, weight='length') for path in p2p_path])
              self.cost_dict[str(count)] = {z:[i,j] for i,j,z in zip(combinations_p[:,ix], combinations_p[:,ix+1], path_cost)}
              count += 1
          else:
            if type(combinations_p[0][0]) != int and self.time_position:
              combinations_p = [i[0] + [i[1]] for i in combinations_p]
              combinations_p = np.array(combinations_p)
              for ix in range(combinations_p.shape[1]-1):
                p2p_path = ox.shortest_path(self.G, combinations_p[:,ix], combinations_p[:,ix+1], weight='length')
                path_cost = np.array([path_weight(self.G, path, weight='length') for path in p2p_path])
                self.cost_dict[str(count)] = {z:[i,j] for i,j,z in zip(combinations_p[:,ix], combinations_p[:,ix+1], path_cost)}
                count += 1
              self.time_position = False
              
            elif type(combinations_p[0][0]) != int and not self.time_position:
              combinations_p = [[i[0][-1], i[1]] for i in combinations_p]
              combinations_p = np.array(combinations_p)
              p2p_path = ox.shortest_path(self.G, combinations_p[:,0], combinations_p[:,1], weight='length')
              path_cost = np.array([path_weight(self.G, path, weight='length') for path in p2p_path])
              self.cost_dict[str(count)] = {j:list(i) for i,j in zip(combinations_p, path_cost)}
              count += 1            
            else:
              combinations_p = np.array(combinations_p)
              p2p_path = ox.shortest_path(self.G, combinations_p[:,0], combinations_p[:,1], weight='length')
              path_cost = np.array([path_weight(self.G, path, weight='length') for path in p2p_path])
              self.cost_dict[str(count)] = {j:list(i) for i,j in zip(combinations_p, path_cost)}
              count += 1
          start = end

    def get_paths(self):
        all_nodes = [list(self.cost_dict[str(i+1)].values()) for i in range(len(self.cost_dict))]
        result = all_nodes[0]
        for x in range(len(all_nodes)-1):
          if x == len(all_nodes)-2:
            result = [i for i in list(product(*[result, all_nodes[x+1]])) if list(i)[0][-1] == list(i)[1][0]]
            result = [list(i[0]+i[1]) for i in result]
          else:
            result = [i for i in list(product(*[result, all_nodes[x+1]])) if list(i)[0][-1] == list(i)[1][0] and list(i)[1][1] not in list(i)[0]]
            result = [list(i[0]+i[1]) for i in result]

        return np.array(result)

    def get_distance_and_route(self, result):
        start = 0
        end = 2
        path_cost_array = []

        for x in range(len(self.cost_dict)):
          current_nodes = result[:,start:end]
          start = end
          end += 2
          current_nodes_cost_index = [list(self.cost_dict[str(x+1)].values()).index(list(i)) for i in current_nodes]
          current_nodes_cost = [list(self.cost_dict[str(x+1)].keys())[i] for i in current_nodes_cost_index]
          path_cost_array.append(current_nodes_cost)

        path_cost_array = np.array(path_cost_array).T
        best_costs = [sum(i) for i in path_cost_array]
        best_path_index = best_costs.index(min(best_costs))
        best_path = result[best_path_index]
        best_path_costs = [str(round(i/1000,2))+'km' for i in path_cost_array[best_path_index]]
        best_path_name = [self.mapping[i] for i in list(OrderedDict.fromkeys(list(best_path)))]

        return best_path_costs, best_path_name

    def plan_travel(self):
        self.get_cost_dict()
        paths = self.get_paths()
        distance, route = self.get_distance_and_route(paths)

        return distance, route
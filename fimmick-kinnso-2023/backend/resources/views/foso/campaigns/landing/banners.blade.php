@extends('foso.layouts.default')

@section('page_title', 'Key Visual & Banner')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.landing.keyvisuals.html") }}'>Key Visual &amp; Banner</a></li>
@endsection

@section('content')

<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.landing.keyvisuals.html") }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-film"></i></span> <span class="hidden-xs-down">Key Visuals</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.landing.topics.html") }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-filter"></i></span> <span class="hidden-xs-down">Topics</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.landing.categories.html") }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-tags"></i></span> <span class="hidden-xs-down">Categories</span></a> </li>
	<li class="nav-item"> <a class="nav-link active" href='{{ route("foso.campaigns.landing.banners.html") }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-file-image-o"></i></span> <span class="hidden-xs-down">Banners</span></a> </li>
	<li class="nav-item"> <a class="nav-link" href='{{ route("foso.campaigns.landing.hotoffers.html") }}' role="tab"><span class="hidden-sm-up"><i class="fa fa-credit-card"></i></span> <span class="hidden-xs-down">Hot Offers</span></a> </li>
</ul>

<div class='card' style="margin-bottom:10px">
	<div class='card-body'>
		<div class="row">
			<div class="col-sm-12">
				<h4>Banner</h4>
				array("image"=>"https://www.kinnso.com/images/icon_01.png", "url"=>"https://www.kinnso.com/"),
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
	Sortable.create(keyVisualList,  {
		// Element dragging ended
		onEnd: async function(evt)  {
			var itemEl = evt.item; // dragged HTMLElement
			evt.to; // target list
			evt.from; // previous list
			let oldIndex = evt.oldIndex; // element's old index within old parent
			let newIndex = evt.newIndex; // element's new index within new parent
			evt.oldDraggableIndex; // element's old index within old parent, only counting draggable elements
			evt.newDraggableIndex; // element's new index within new parent, only counting draggable elements
			evt.clone // the clone element
			evt.pullMode; // when item is in another sortable: `"clone"` if cloning, `true` if moving
			rearangeOffersPermutation(oldIndex, newIndex)
		},
	});

	const rearangeOffersPermutation = async (oldIndex, newIndex) => {
		await fetch('', {
			method: 'POST',
			headers:  {'Content-Type':'application/json'},
			body: JSON.stringify({
				'listName': currentListName,
				'oldIndex': oldIndex,
				'newIndex': newIndex,
			})
		})
		.then(res => res.json())
		.then(json => {
			if (json.status == 0)  {}
			else  {alert(json.message)}
		});
	}
</script>

@endsection

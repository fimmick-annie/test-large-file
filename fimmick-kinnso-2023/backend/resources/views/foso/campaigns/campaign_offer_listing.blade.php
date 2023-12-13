@extends('foso.layouts.default')

@section('page_title', 'Campaign Offer List Management')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i> Offer List Management</li>
@endsection

@section('content')

<div class='card'>
	<div class='card-body'>
		<div class='row'>
			<div class='col-sm-6'>
				<div>Current List</div>
				<select id='listDropDown' class='form-control'></select>
			</div>
			<div class='col-sm-3'>
				<div>&nbsp;</div>
				<button class='btn btn-outline-success btn-block' data-toggle="modal" data-target="#exampleModal">Create List</button>
			</div>
			<div class='col-sm-3'>
				<div>&nbsp;</div>
				<button class='btn btn-success btn-block' data-toggle="modal" data-target="#exampleModal2">Add Offer</button>
			</div>

			<div class='col-sm-12'>
				<small>Please drag & drop below blocks to change ordering.</small>
			</div>
		</div>

		<!-- Simple List -->
		<div id="simpleList" class="list-group"></div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add New List</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<label class='w-100'>List Name</label>
				<input class='w-100' id='createNewListInput' type='text' name='listName'></input>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="createNewList()">Submit </button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModal2Label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModal2Label">Add Offer Into Current List</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<label class='w-100'>Offer Id</label>
				<input class='w-100' id='addOfferId' type='text' name='offerId'></input>
				<label class='w-100'>Start At</label>
				<input class='w-100' id='addOfferStartAt' type='datetime-local' name='startAt'></input>
				<label class='w-100'>End At</label>
				<input class='w-100' id='addOfferEndAt' type='datetime-local' name='endAt'></input>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="addOfferIntoList()">Submit </button>
			</div>
		</div>
	</div>
</div>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#exampleModal3" id='exampleModal3Btn'>
	Launch demo modal
</button>
<!-- Modal -->
<div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModal3Label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModal3Label">Update Offer </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<label class='w-100'>Offer Id</label>
				<input class='w-100' id='updateOfferId' type='text' name='offerId' disabled></input>
				<label class='w-100'>Start At</label>
				<input class='w-100' id='updateOfferStartAt' type='datetime-local' name='startAt'></input>
				<label class='w-100'>End At</label>
				<input class='w-100' id='updateOfferEndAt' type='datetime-local' name='endAt'></input>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="updateOffer()">Submit </button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{ asset('js/common.js') }}?v=1"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="/js/animatedBtn.js"></script>


<script>
	Sortable.create(simpleList, {
		// Element dragging ended
		onEnd: async function( /**Event*/ evt) {
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

	let currentListName = '';
	let currentOfferId = 0;
	let list = [];
	let offers = [];
	const listDropDown = document.querySelector('#listDropDown');
	const offerlist = document.querySelector('#simpleList');
	const createNewListInput = document.querySelector('#createNewListInput');
	const addOfferId = document.querySelector('#addOfferId');
	const addOfferStartAt = document.querySelector('#addOfferStartAt');
	const addOfferEndAt = document.querySelector('#addOfferEndAt');
	const updateOfferId = document.querySelector('#updateOfferId');
	const updateOfferStartAt = document.querySelector('#updateOfferStartAt');
	const updateOfferEndAt = document.querySelector('#updateOfferEndAt');
	const exampleModal3Btn = document.querySelector('#exampleModal3Btn');
	const getList = async () => {
		// get offer listing list
		await fetch('{{ route("foso.campaigns.offerlist.getlist.json") }}')
			.then(res => res.json())
			.then(json => {
				list = json;
				let html = json.map(el => {
					return `<option value='${el.list_name}'>${el.list_name}</option>`
				}).join('');
				listDropDown.innerHTML = html;
			})
	}
	const getOffersByList = async (listName) => {
		// get offers by list
		currentListName = listName;

		await fetch('{{ route("foso.campaigns.offerlist.getoffersbylist.json") }}', {
				method: 'post',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({
					'listName': listName
				})
			})
			.then(res => res.json())
			.then(json => {
				offers = json;
				let html = json.map(el => {
					return `
						<div class="list-group-item my-3">
							<div class='row'>
								<div class='col-6 d-flex flex-column justify-content-between'>
									<div>
										<div class='p-1'><b>#${el.offer_id}: ${el.offer_name}</b></div>
										<div class='p-1'>Start At : ${el.start_at}</div>
										<div class='p-1'>End At : ${el.end_at}</div>
									</div>
									<div>
										<button data-offerid='${el.offer_id}' class='remove-btn btn btn-danger'>Remove Offer from the List</button>
										<button data-offerid='${el.offer_id}' class='update-btn btn btn-success'>Update Offer </button>
									</div>
								</div>
								<div class='col-6 d-flex justify-content-end'>
									<img style='max-height: 250px' src='/offers/${el.offer_name}/offer_thumbnail.png'>
								</div>
							</div>
						</div>
					`
				}).join('');
				offerlist.innerHTML = html;
				document.querySelectorAll('.remove-btn').forEach(el => {
					el.addEventListener('mousedown', e => {
						currentOfferId = e.target.dataset.offerid;
					});
					el.addEventListener('mouseup', e => {
						currentOfferId = 0;
					});
				})
				document.querySelectorAll('.update-btn').forEach(el => {
					el.addEventListener('click', handleUpdateOfferModal)
				})
				AnimatedBtn({
					domClass: 'remove-btn',
					transitionTime: 2000,
					yourFunction: removeOfferFromList,
					coverColor: 'rgba(128, 40, 40, 1)',
					baseColor: 'rgba(221, 75, 57, 1)',
				})
			})
	}

	const createNewList = async () => {
		let listName = createNewListInput.value;
		if (!listName) {
			alert('List Name can\'t be empty.')
			return;
		}
		await fetch('{{ route("foso.campaigns.offerlist.createnewlist.json") }}', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({
					'listName': listName
				})
			})
			.then(res => res.json())
			.then(json => {
				if (json.status == 0) {
					alert('Success.')
					location.reload();
				} else {
					alert(json.message)
				}
			});
	}
	const rearangeOffersPermutation = async (oldIndex, newIndex) => {
		// place offer another position and shift remain offers backward
		await fetch('{{ route("foso.campaigns.offerlist.rearangeofferspermutation.json") }}', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({
					'listName': currentListName,
					'oldIndex': oldIndex,
					'newIndex': newIndex,
				})
			})
			.then(res => res.json())
			.then(json => {
				if (json.status == 0) {
					getOffersByList(currentListName);
					// alert('Success.')
					// location.reload();
				} else {
					alert(json.message)
				}
			});
	}
	const addOfferIntoList = async () => {
		let offerId = addOfferId.value;
		let startAt = addOfferStartAt.value;
		let endAt = addOfferEndAt.value;

		if (!offerId || !startAt || !endAt) {
			alert('Inputs can\'t be empty.')
			return;
		}
		// to timestamp
		startAt = new Date(startAt).getTime()
		endAt = new Date(endAt).getTime()

		await fetch('{{ route("foso.campaigns.offerlist.addofferintolist.json") }}', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({
					'listName': currentListName,
					'offerId': offerId,
					'startAt': startAt,
					'endAt': endAt
				})
			})
			.then(res => res.json())
			.then(json => {
				if (json.status == 0) {
					getOffersByList(currentListName);
					// alert('Success.')
					// location.reload();
				} else {
					alert(json.message)
				}
			});
	}
	const handleUpdateOfferModal = async (e) => {
		updateOfferId.value = e.currentTarget.dataset.offerid;
		updateOfferStartAt.value = '';
		updateOfferEndAt.value = '';
		exampleModal3Btn.click();
	}
	const updateOffer = async () => {
		let offerId = updateOfferId.value;
		let startAt = updateOfferStartAt.value;
		let endAt = updateOfferEndAt.value;

		if (!offerId || !startAt || !endAt) {
			alert('Inputs can\'t be empty.')
			return;
		}
		// to timestamp
		startAt = new Date(startAt).getTime()
		endAt = new Date(endAt).getTime()

		await fetch('{{ route("foso.campaigns.offerlist.updateofferintolist.json") }}', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({
					'listName': currentListName,
					'offerId': offerId,
					'startAt': startAt,
					'endAt': endAt
				})
			})
			.then(res => res.json())
			.then(json => {
				if (json.status == 0) {
					getOffersByList(currentListName);
					exampleModal3Btn.click();

					// alert('Success.')
					// location.reload();
				} else {
					alert(json.message)
				}
			});
	}
	const removeOfferFromList = async () => {
		await fetch('{{ route("foso.campaigns.offerlist.removeofferintolist.json") }}', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({
					'listName': currentListName,
					'offerId': currentOfferId,
				})
			})
			.then(res => res.json())
			.then(json => {
				if (json.status == 0) {
					getOffersByList(currentListName);
					// alert('Success.')
					// location.reload();
				} else {
					alert(json.message)
				}
			});
	}

	window.addEventListener('load', async () => {
		await getList();
		await getOffersByList(listDropDown.value);
		listDropDown.addEventListener('change', (e) => {
			getOffersByList(e.target.value);
		})
	})
</script>

@endsection

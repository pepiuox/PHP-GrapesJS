<li class="pi-draggable actuve item" draggable="true" ondragstart="return dragStart(event)">
	<div class="alert alert-primary" role="alert">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<h4 class="alert-heading">Well done!</h4>
		<p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and tidy.</p>
	</div>
</li>
<li class="pi-draggable" draggable="true" ondragstart="return dragStart(event)">
	<div class="progress">
		<div class="progress-bar progress-bar-striped" role="progressbar" style="width: 50%">50%</div>
	</div>
</li>
<li class="pi-draggable" draggable="true" ondragstart="return dragStart(event)">
	<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
		<div class="toast-header">
			<strong class="mr-auto">Bootstrap</strong>
			<small class="text-muted">2 seconds ago</small>
			<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="toast-body">
			Heads up, toasts will stack automatically
		</div>
	</div>
</li>
<li class="pi-draggable" draggable="true" ondragstart="return dragStart(event)">
	<div class="modal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Modal title</h5> <button type="button" class="close" data-dismiss="modal"> <span>×</span> </button>
				</div>
				<div class="modal-body">
					<p>Modal body text goes here.</p>
				</div>
				<div class="modal-footer"> <button type="button" class="btn btn-primary">Save changes</button> <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> </div>
			</div>
		</div>
	</div>
</li>
<li class="pi-draggable" draggable="true" ondragstart="return dragStart(event)">
	<!-- Button trigger modal -->
	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
		Launch demo modal
	</button>
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					...
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>
</li>
<li class="pi-draggable" draggable="true" ondragstart="return dragStart(event)">
	<!-- Button trigger modal -->
	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
		Launch static backdrop modal
	</button>
	<!-- Modal -->
	<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					...
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Understood</button>
				</div>
			</div>
		</div>
	</div>
</li>
<li class="pi-draggable" draggable="true" ondragstart="return dragStart(event)">
	<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalToggleLabel">Modal 1</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Show a second modal and hide this one with the button below.
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" data-bs-dismiss="modal">Open second modal</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalToggleLabel2">Modal 2</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Hide this modal and show the first with the button below.
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal" data-bs-dismiss="modal">Back to first</button>
				</div>
			</div>
		</div>
	</div>
	<a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Open first modal</a>
</li>

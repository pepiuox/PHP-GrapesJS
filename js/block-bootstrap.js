let blockManager = editor.BlockManager;
blockManager.add('covers1', {
    label: '<div class="gjs-block-label">Covers 1</div>',
    attributes: {class: "fa fa-id-card-o"},
    category: "Covers",
    activate: 1,
    content: '<div class="py-5 text-center h-100 align-items-center d-flex">' +
            '<div class="container py-5">' +
            '<div class="row">' +
            '<div class="mx-auto col-lg-8 col-md-10">' +
            '<h1 class="display-3 mb-4">A wonderful serenity</h1>' +
            '<p class="lead mb-5">Has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence.</p> <a href="#" class="btn btn-lg btn-primary mx-1">Take me there</a> <a class="btn btn-lg mx-1 btn-outline-primary" href="#">Go</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>'
});
blockManager.add('covers2', {
    label: '<div class="gjs-block-label">Covers 2</div>',
    attributes: {class: "fa fa-id-card-o"},
    category: "Covers",
    activate: 1,
    content: '<div class="py-5 text-center">' +
            '<div class="container">' +
            '<div class="row">' +
            '<div class="bg-white p-5 mx-auto col-md-8 col-10">' +
            '<h3 class="display-3">I feel the charm</h3>' +
            '<p class="mb-3 lead">Of existence in this spot</p>' +
            '<p class="mb-4">Which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.</p> <a class="btn btn-outline-primary" href="#">Read more</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>'
});
blockManager.add("card", {
    label: '<div class="gjs-block-label">Card</div>',
    attributes: {class: "fa fa-id-card-o"},
    category: "Cards",
    activate: 1,
    content: '<div class="card">' +
            '<img class="card-img-top" src="..." alt="..." >' +
            '<div class="card-body">' +
            '<h4 class="card-title">Card title</h4>' +
            '<p class="card-text">Some quick example text to build on the card title content.</p>' +
            '<a href="#" class="btn btn-primary">Go somewhere</a>' +
            '</div>' +
            '</div>'
});
blockManager.add("card", {
    label: '<div class="gjs-block-label">Card</div>',
    attributes: {class: "fa fa-id-card-o"},
    category: "Cards"
});
blockManager.add("input", {
    label: '<div class="gjs-block-label">Input</div>',
    attributes: {class: "fa fa-id-card-o"},
    category: "Inputs"
});
blockManager.add("form", {
    label: '<div class="gjs-block-label">Form</div>',
    attributes: {class: "fa fa-id-card-o"},
    category: "Forms"
});
blockManager.add("grid", {
    label: '<div class="gjs-block-label">Grid</div>',
    attributes: {class: "fa fa-id-card-o"},
    category: "Grids"
});
blockManager.add("nav", {
    label: '<div class="gjs-block-label">Nav</div>',
    attributes: {class: "fa fa-id-card-o"},
    category: "Navs"
});
blockManager.add("navbar", {
    label: '<div class="gjs-block-label">Navbars</div>',
    attributes: {class: "fa fa-id-card-o"},
    category: "Navbars"
});
blockManager.add("list", {
    label: '<div class="gjs-block-label">List</div>',
    attributes: {class: "fa fa-id-card-o"},
    category: "List"
});
blockManager.add("media", {
    label: '<div class="gjs-block-label">Media</div>',
    attributes: {class: "fa fa-id-card-o"},
    category: "Media"
});

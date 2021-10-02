export default (editor, config = {}) => {
    const bm = editor.BlockManager;
    const {cardBlock} = opts;
    const style = opts.style;

    const type = "card";

    const content = `<div data-gjs-type="${type}"></div>
    ${style ? `<style>${style}</style>` : ""}`;

    cardBlock &&
            bm.add("card", {
                label: "Card",
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
                        '</div>',
                // : {
                //   type: "table",
                //   activeOnRender: true,
                // },
                ...cardBlock,
            });
}

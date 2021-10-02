import windowIcon from "raw-loader!../icons/window-maximize-solid.svg";

export const FigureBlock = (bm, label) => {
    bm.add('figure').set({
        label: `
            ${windowIcon}
            <div>${label}</div>
        `,
        category: 'Layout',
        content: {
            type: 'figure',
            classes: ['figure']
        }
    });
};

export default (domc, editor) => {
    const comps = editor.DomComponents;
    const defaultType = domc.getType('default');
    const defaultModel = defaultType.model;
    const defaultView = defaultType.view;

    domc.addType('figure', {
        model: defaultModel.extend({
            defaults: Object.assign({}, defaultModel.prototype.defaults, {
                'custom-name': 'Figure',
                tagName: 'div',
                draggable: '.container, .container-fluid',
                droppable: true,
                traits: [
                    {
                        type: 'class_select',
                        options: [
                            {value: '', name: 'Yes'},
                            {value: 'no-gutters', name: 'No'}
                        ],
                        label: 'Gutters?'
                    }
                ].concat(defaultModel.prototype.defaults.traits)
            })
        }, {
            isComponent(el) {
                if(el && el.classList && el.classList.contains('figure')) {
                    return {type: 'figure'};
                }
            }
        }),
        view: defaultView
    });
}

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

export default class DynamicContentBlocks {
  constructor(editor, opts = {}) {
    _defineProperty(this, "editor", void 0);

    _defineProperty(this, "opts", void 0);

    _defineProperty(this, "blockManager", void 0);

    this.editor = editor;
    this.opts = opts;
    this.blockManager = this.editor.BlockManager;
  }

  addDynamciContentBlock() {
    this.blockManager.add('dynamic-content', {
      label: Mautic.translate('grapesjsbuilder.dynamicContentBlockLabel'),
      activate: true,
      select: true,
      attributes: {
        class: 'fa fa-tag'
      },
      content: {
        type: 'dynamic-content',
        content: '{dynamiccontent="Dynamic Content"}',
        style: {
          padding: '10px'
        },
        activeOnRender: 1
      }
    });
  }

}
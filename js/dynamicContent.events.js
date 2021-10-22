function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

import DynamicContentCommands from './dynamicContent.commands';
import DynamicContentService from './dynamicContent.service';
export default class DynamicContentEvents {
  constructor(editor) {
    _defineProperty(this, "editor", void 0);

    _defineProperty(this, "dcService", void 0);

    this.editor = editor;
    this.dcService = new DynamicContentService(this.editor);
    this.dccmd = new DynamicContentCommands(this.editor);
  } // @todo merge events and listeners. or move this to the component itself as a
  // local listener. see create-new-dynamic-content-store-item


  onComponentRemove() {
    this.editor.on('component:remove', component => {
      // Delete dynamic-content on Mautic side
      if (component.get('type') === 'dynamic-content') {
        this.editor.runCommand('preset-mautic:dynamic-content-delete-store-item', {
          component
        });
      }
    });
  }

}
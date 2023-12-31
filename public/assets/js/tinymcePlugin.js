const tinymcePlugin = (editor, opts = {}) => {
    const options = {...{
                // TinyMCE options
                options: {},

                // On which side of the element to position the toolbar
                // Available options: 'left|center|right'
                position: 'left',
        }, ...opts};

    if (!tinymce) {
        throw new Error('tinymce not found');
    }

    editor.setCustomRte({
        enable(el, rte = {}) {
            const {activeEditor} = rte;
            const rteToolbar = editor.RichTextEditor.getToolbarEl();
            const tlbSelector = `.${rteToolbar.className.split(' ').join('.')}`;
            el.contentEditable = true;

            // Hide everything inside the GrapesJS' toolbar container
            [].forEach.call(rteToolbar.children, child => child.style.display = 'none');

            // If already exists I'll just focus on it
            if (activeEditor) {
                activeEditor.show();
                activeEditor.focus();
                return rte;
            }

            // Init TinyMCE
            rte = tinymce.EditorManager.init({
                target: el,
                inline: true,
                menubar: false,
                forced_root_block: '', // avoid '<p>' wrapper
                fixed_toolbar_container: tlbSelector, // place the toolbar inside GrapesJS' toolbar container
                setup(ed) {
                    ed.on('init', e => ed.focus());
                    // Fix the position of the toolbar when the editor is created
                    ed.once('focus', e => setTimeout(() => editor.trigger('canvasScroll')));
                },
                ...options.options,
            });

            // The init method returns a Promise, so we need to store the editor instance on it
            rte.then(result => rte.activeEditor = result[0]);

            return rte;
        },

        disable(el, rte = {}) {
            const {activeEditor} = rte;
            el.contentEditable = false;
            activeEditor && activeEditor.hide();
        },
    });

    // Update RTE toolbar position
    editor.on('rteToolbarPosUpdate', (pos) => {
        // Update by position
        switch (options.position) {
            case 'center':
                let diff = (pos.elementWidth / 2) - (pos.targetWidth / 2);
                pos.left = pos.elementLeft + diff;
                break;
            case 'right':
                let width = pos.targetWidth;
                pos.left = pos.elementLeft + pos.elementWidth - width;
                break;
        }

        if (pos.top <= pos.canvasTop) {
            pos.top = pos.elementTop + pos.elementHeight;
        }

        // Check if not outside of the canvas
        if (pos.left < pos.canvasLeft) {
            pos.left = pos.canvasLeft;
        }
    });

};

const editor = grapesjs.init({
    container: '#gjs',
    fromElement: 1,
    height: '100%',
    storageManager: {type: 0},
    plugins: ['gjs-blocks-basic', tinymcePlugin]
});


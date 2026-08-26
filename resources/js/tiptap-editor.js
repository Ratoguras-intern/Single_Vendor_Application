import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';

export function initTiptapEditor(hiddenSelector = '#content-hidden', editorSelector = '#editor') {
    const hiddenEl = document.querySelector(hiddenSelector);
    const editorEl = document.querySelector(editorSelector);
    if (!hiddenEl || !editorEl) return null;

    const editor = new Editor({
        element: editorEl,
        extensions: [
            StarterKit.configure({ heading: { levels: [1, 2, 3] } }),
            Underline,
            Link.configure({ openOnClick: false, HTMLAttributes: { class: 'text-blue-600 underline hover:text-blue-800' } }),
            Placeholder.configure({ placeholder: 'Start writing your page content here...' }),
        ],
        content: hiddenEl.value || '',
        onUpdate: ({ editor }) => { hiddenEl.value = editor.getHTML(); },
        onCreate: ({ editor }) => { hiddenEl.value = editor.getHTML(); },
    });

    document.querySelectorAll('.toolbar-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const action = this.dataset.action;
            switch (action) {
                case 'heading':
                    const lvl = editor.isActive('heading', { level: 2 }) ? 3
                        : editor.isActive('heading', { level: 3 }) ? 1
                        : editor.isActive('heading', { level: 1 }) ? 2 : 2;
                    editor.chain().focus().toggleHeading({ level: lvl }).run(); break;
                case 'bold': editor.chain().focus().toggleBold().run(); break;
                case 'italic': editor.chain().focus().toggleItalic().run(); break;
                case 'underline': editor.chain().focus().toggleUnderline().run(); break;
                case 'bulletList': editor.chain().focus().toggleBulletList().run(); break;
                case 'orderedList': editor.chain().focus().toggleOrderedList().run(); break;
                case 'blockquote': editor.chain().focus().toggleBlockquote().run(); break;
                case 'horizontalRule': editor.chain().focus().setHorizontalRule().run(); break;
                case 'link':
                    const currentHref = editor.getAttributes('link').href || 'https://';
                    window.showPrompt('Enter URL:', currentHref).then(url => {
                        if (url !== null) {
                            if (url === '') editor.chain().focus().extendMarkRange('link').unsetLink().run();
                            else editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
                        }
                    }); break;
                case 'undo': editor.chain().focus().undo().run(); break;
                case 'redo': editor.chain().focus().redo().run(); break;
            }
            updateToolbarState(editor);
        });
    });

    function updateToolbarState(editor) {
        document.querySelectorAll('.toolbar-btn').forEach(btn => {
            const action = btn.dataset.action;
            let isActive = false;
            switch (action) {
                case 'bold': isActive = editor.isActive('bold'); break;
                case 'italic': isActive = editor.isActive('italic'); break;
                case 'underline': isActive = editor.isActive('underline'); break;
                case 'bulletList': isActive = editor.isActive('bulletList'); break;
                case 'orderedList': isActive = editor.isActive('orderedList'); break;
                case 'blockquote': isActive = editor.isActive('blockquote'); break;
                case 'link': isActive = editor.isActive('link'); break;
            }
            btn.classList.toggle('active', isActive);
        });
    }

    editor.on('selectionUpdate', () => updateToolbarState(editor));
    return editor;
}

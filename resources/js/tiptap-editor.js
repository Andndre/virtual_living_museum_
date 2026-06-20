import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';

window.PanoramaTiptapEditor = {
    _editor: null,

    init(initialContent = '') {
        const el = document.getElementById('tiptap-editor');
        if (!el) return;

        this.destroy();

        this._editor = new Editor({
            element: el,
            extensions: [
                StarterKit.configure({ codeBlock: false, link: false, underline: false }),
                Underline,
                Link.configure({ openOnClick: false }),
                Image.configure({ inline: false }),
            ],
            content: initialContent || '',
            editorProps: {
                attributes: {
                    class: 'prose prose-sm max-w-none p-3 min-h-[120px] focus:outline-none text-sm text-gray-800',
                },
            },
            onUpdate: ({ editor }) => {
                el.dispatchEvent(new CustomEvent('tiptap-update', {
                    detail: { html: editor.getHTML() },
                    bubbles: true,
                }));
            },
        });
    },

    setContent(html) {
        this._editor?.commands.setContent(html || '', false);
    },

    getHTML() {
        return this._editor?.getHTML() ?? '';
    },

    destroy() {
        if (this._editor) {
            this._editor.destroy();
            this._editor = null;
        }
    },

    toggleBold()        { this._editor?.chain().focus().toggleBold().run(); },
    toggleItalic()      { this._editor?.chain().focus().toggleItalic().run(); },
    toggleUnderline()   { this._editor?.chain().focus().toggleUnderline().run(); },
    toggleStrike()      { this._editor?.chain().focus().toggleStrike().run(); },
    toggleBulletList()  { this._editor?.chain().focus().toggleBulletList().run(); },
    toggleOrderedList() { this._editor?.chain().focus().toggleOrderedList().run(); },

    setLink() {
        const prev = this._editor?.getAttributes('link').href || '';
        const url = prompt('Masukkan URL link:', prev);
        if (url === null) return;
        if (url === '') {
            this._editor?.chain().focus().unsetLink().run();
        } else {
            this._editor?.chain().focus().setLink({ href: url, target: '_blank' }).run();
        }
    },

    insertImageUrl(url) {
        if (url) {
            this._editor?.chain().focus().setImage({ src: url }).run();
        }
    },

    clearFormat() {
        this._editor?.chain().focus().unsetAllMarks().clearNodes().run();
    },
};

// Auto-init on panorama editor page once DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('tiptap-editor')) {
        window.PanoramaTiptapEditor.init('');
    }
});

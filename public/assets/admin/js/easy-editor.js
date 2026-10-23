/**
 * Easy Editor - Custom Rich Text Editor
 * A powerful, customizable rich text editor replacement for TinyMCE
 * Features: All TinyMCE features + Dashboard color scheme
 */

class EasyEditor {
    constructor(selector, options = {}) {
        this.selector = selector;
        
        // Check if syntax module is available (requires highlight.js)
        const hasSyntaxModule = typeof hljs !== 'undefined';
        
        // Build toolbar - table and imageResize modules removed to avoid errors
        const toolbar = [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'font': [] }, { 'size': [] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'script': 'sub'}, { 'script': 'super' }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
            [{ 'direction': 'rtl' }],
            [{ 'align': [] }],
            ['link', 'image', 'video'],
            ['blockquote', 'code-block'],
            ['clean']
        ];
        
        // Build modules object - only include modules that are available and working
        const modules = {
            toolbar: toolbar
        };
        
        // Only add syntax module if highlight.js is available
        if (hasSyntaxModule) {
            modules.syntax = true;
        }
        
        // Explicitly do NOT include table or imageResize modules to avoid errors
        
        this.defaultOptions = {
            theme: 'snow',
            placeholder: 'Start typing...',
            modules: modules,
            bounds: document.body,
            debug: false
        };
        
        this.options = this.mergeOptions(this.defaultOptions, options);
        this.editors = [];
        this.init();
    }

    mergeOptions(defaultOpts, customOpts) {
        const merged = { ...defaultOpts };
        if (customOpts.modules) {
            merged.modules = { ...defaultOpts.modules, ...customOpts.modules };
            if (customOpts.modules.toolbar) {
                merged.modules.toolbar = { ...defaultOpts.modules.toolbar, ...customOpts.modules.toolbar };
            }
        }
        return { ...merged, ...customOpts };
    }

    imageHandler(quill) {
        if (!quill) quill = this.currentQuill;
        if (!quill) return;

        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.style.display = 'none';
        document.body.appendChild(input);
        input.click();
        
        input.onchange = () => {
            const file = input.files[0];
            if (file) {
                // Check file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Image size must be less than 5MB');
                    document.body.removeChild(input);
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    const range = quill.getSelection(true);
                    quill.insertEmbed(range.index, 'image', e.target.result);
                    quill.setSelection(range.index + 1);
                };
                reader.readAsDataURL(file);
            }
            document.body.removeChild(input);
        };
    }

    videoHandler(quill) {
        if (!quill) quill = this.currentQuill;
        if (!quill) return;

        const url = prompt('Enter video URL (YouTube, Vimeo, etc.):');
        if (url) {
            const range = quill.getSelection(true);
            quill.insertEmbed(range.index, 'video', url);
            quill.setSelection(range.index + 1);
        }
    }

    linkHandler(quill, value) {
        if (!quill) quill = this.currentQuill;
        if (!quill) return;

        if (value) {
            const range = quill.getSelection(true);
            if (range) {
                quill.format('link', value);
            }
        } else {
            const range = quill.getSelection(true);
            if (range && range.length > 0) {
                const link = quill.getFormat(range)['link'];
                const url = prompt('Enter URL:', link || 'https://');
                if (url) {
                    quill.format('link', url);
                }
            }
        }
    }

    init() {
        if (typeof Quill === 'undefined') {
            console.error('Easy Editor: Quill library not loaded. Please include Quill CSS and JS files.');
            return;
        }

        const elements = document.querySelectorAll(this.selector);
        elements.forEach((element, index) => {
            if (element.classList.contains('easy-editor-initialized')) {
                return;
            }

            // Store original textarea value
            const originalValue = element.value || element.innerHTML || '';
            
            // Create editor container
            const editorId = `easy-editor-${Date.now()}-${index}`;
            const editorContainer = document.createElement('div');
            editorContainer.id = editorId;
            editorContainer.className = 'easy-editor-container';
            element.style.display = 'none';
            element.parentNode.insertBefore(editorContainer, element.nextSibling);

            // Set current quill context
            this.currentQuill = null;

            // Initialize Quill
            const quill = new Quill(`#${editorId}`, this.options);
            this.currentQuill = quill;
            
            // Add custom handlers
            const toolbar = quill.getModule('toolbar');
            toolbar.addHandler('image', () => this.imageHandler(quill));
            toolbar.addHandler('video', () => this.videoHandler(quill));
            toolbar.addHandler('link', (value) => this.linkHandler(quill, value));
            
            // Set initial content
            if (originalValue) {
                quill.root.innerHTML = originalValue;
            }

            // Sync with original textarea
            quill.on('text-change', () => {
                element.value = quill.root.innerHTML;
                // Trigger change event for form validation
                const event = new Event('change', { bubbles: true });
                element.dispatchEvent(event);
            });

            // Store editor instance
            this.editors.push({
                element: element,
                quill: quill,
                container: editorContainer
            });

            element.classList.add('easy-editor-initialized');
        });
    }

    getEditor(element) {
        return this.editors.find(e => e.element === element)?.quill;
    }

    getContent(element) {
        const editor = this.getEditor(element);
        return editor ? editor.root.innerHTML : '';
    }

    setContent(element, content) {
        const editor = this.getEditor(element);
        if (editor) {
            editor.root.innerHTML = content;
        }
    }

    destroy(element) {
        const index = this.editors.findIndex(e => e.element === element);
        if (index !== -1) {
            const editor = this.editors[index];
            editor.quill = null;
            editor.container.remove();
            editor.element.style.display = '';
            editor.element.classList.remove('easy-editor-initialized');
            this.editors.splice(index, 1);
        }
    }

    destroyAll() {
        this.editors.forEach(editor => {
            this.destroy(editor.element);
        });
    }
}

// jQuery plugin wrapper
if (typeof jQuery !== 'undefined') {
    (function($) {
        $.fn.easyEditor = function(options) {
            const selector = this.selector || this.attr('class') || this.attr('id');
            const editor = new EasyEditor(selector, options);
            return this;
        };
    })(jQuery);
}

// Global initialization function
window.initEasyEditor = function(selector, options) {
    return new EasyEditor(selector, options);
};

// Auto-initialize on DOM ready
function initEasyEditors() {
    const selectors = '.code-editor-tiny, .code-editor';
    const elements = document.querySelectorAll(selectors);
    if (elements.length > 0) {
        window.easyEditorInstance = new EasyEditor(selectors);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(initEasyEditors, 100);
    });
} else {
    setTimeout(initEasyEditors, 100);
}

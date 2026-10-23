# Easy Editor - Custom Rich Text Editor

Easy Editor is a custom rich text editor that replaces TinyMCE in the dashboard. It's built on top of Quill.js and styled to match the dashboard color scheme.

## Features

- ✅ All TinyMCE features
- ✅ Dashboard color scheme integration
- ✅ Image upload and resizing
- ✅ Video embedding
- ✅ Table support
- ✅ Code blocks
- ✅ Text formatting (bold, italic, underline, strike)
- ✅ Headers (H1-H6)
- ✅ Lists (ordered, unordered)
- ✅ Text alignment
- ✅ Text color and background color
- ✅ Links
- ✅ Blockquotes
- ✅ RTL support
- ✅ Responsive design

## Installation

The Easy Editor is already integrated into the dashboard layout. No additional installation is required.

## Usage

### Basic Usage

```html
<textarea class="code-editor" name="content"></textarea>
```

or

```html
<textarea class="code-editor-tiny" name="content"></textarea>
```

### JavaScript Initialization

```javascript
// Auto-initializes on page load for .code-editor and .code-editor-tiny classes
// Or manually initialize:
const editor = new EasyEditor('.code-editor');
```

### jQuery Plugin

```javascript
$('.code-editor').easyEditor();
```

## Configuration

The editor can be customized by passing options:

```javascript
const editor = new EasyEditor('.code-editor', {
    placeholder: 'Start typing...',
    theme: 'snow',
    modules: {
        toolbar: [
            // Custom toolbar configuration
        ]
    }
});
```

## File Structure

- `public/assets/admin/js/easy-editor.js` - Main editor JavaScript
- `public/assets/admin/css/easy-editor.css` - Editor styles with dashboard colors

## Dependencies

- Quill.js 1.3.6 (loaded from CDN)
- Quill Table Module (loaded from CDN)

## Migration from TinyMCE

All TinyMCE instances have been replaced with Easy Editor. The following changes were made:

1. Removed TinyMCE script loading from `layouts/dashboard/app.blade.php`
2. Added Quill.js and Easy Editor scripts
3. Updated all `tinymce.init()` calls to `new EasyEditor()`
4. Updated all `.tox.tox-tinymce` selectors to `.easy-editor-container`

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Notes

- Image uploads are limited to 5MB
- Video embedding supports YouTube, Vimeo, and direct video URLs
- The editor automatically syncs content with the original textarea element

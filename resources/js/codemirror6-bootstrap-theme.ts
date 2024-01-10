/**
 * From https://github.com/logue/codemirror6-bootstrap-theme
 * npm package does not work, so code duplicated here.
 */

import { EditorView } from '@codemirror/view';
import type { Extension } from '@codemirror/state';
import { HighlightStyle, syntaxHighlighting } from '@codemirror/language';
import { tags as t } from '@lezer/highlight';

import { toJSON } from 'css-convert-json';
import bootstrap from 'bootstrap/dist/css/bootstrap.css?raw';

/** Bootstrap theme */
const style = toJSON(bootstrap).children;

/** Root theme */
const root = style[':root'].attributes;
// console.log(root);

// Generic Bootstrap Colors
const blue = root['--bs-blue']; // Equals to primary
const indigo = root['--bs-indigo'];
const purple = root['--bs-purple'];
const pink = root['--bs-pink'];
const red = root['--bs-red']; // Equals to danger
const orange = root['--bs-orange'];
const yellow = root['--bs-yellow']; // Equals to warning
const green = root['--bs-green']; // Equals to success
const teal = root['--bs-teal'];
const cyan = root['--bs-cyan']; // Equals to info
const dark = root['--bs-dark'];
const light = root['--bs-light'];
const white = root['--bs-white'];
const black = root['--bs-black'];
const gray = [
  '--bs-white',
  '--bs-gray-100', // Equals to light
  '--bs-gray-200', // assume original light1
  '--bs-gray-300',
  '--bs-gray-400',
  '--bs-gray-500',
  '--bs-gray-600', // Equals to secondary
  '--bs-gray-700',
  '--bs-gray-800', // assume original light1
  '--bs-gray-900', // Equals to dark
];

/** Bootsrap Form Style */
const form = style['.form-control'].attributes;
/** Bootstrap Form Focused style */
const formFocused = style['.form-control:focus'].attributes;
delete formFocused.color;
delete formFocused['background-color'];

/** Editor  */
const editor = form;
delete editor.padding;
delete editor.color;
delete editor['background-color'];

/** Input */
const input = form;
delete input.width;
delete editor.color;
delete editor['background-color'];
input.display = 'inline';

/** Bootstrap Theme */
export const bootstrapTheme: Extension = EditorView.theme(
  {
    '&.cm-editor': {
      ...form,
      '&.cm-focused': {
        ...formFocused,
      },
    },
    '.cm-scroller': {
      fontFamily: root['--bs-font-monospace'],
      lineHeight: root['--bs-body-line-height'],
    },
    '.cm-content': { caretColor: black },
    '.cm-selectionBackground': {
      background: gray[4],
    },
    '.cm-focused .cm-selectionBackground': {
      background: dark,
    },
    '.cm-cursor, .cm-dropCursor': {
      borderLeft: `1.2px solid ${gray[8]}`,
    },
    '.cm-activeLine': {
      color: black,
      backgroundColor: light,
    },
    '.cm-specialChar': {
      color: red,
    },
    '.cm-gutters': {
      color: gray[6],
      backgroundColor: gray[3],
      borderRight: `1px solid ${gray[3]}`,
      borderTopLeftRadius: root['--bs-border-radius'],
      borderBottomLeftRadius: root['--bs-border-radius'],
    },
    '.cm-activeLineGutter': {
      backgroundColor: gray[4],
    },
    '.cm-panels': {
      backgroundColor: gray[3],
      color: black,
    },
    '.cm-panels-top': {
      borderBottom: `1px solid ${gray[4]}`,
    },
    '.cm-panels-bottom': {
      borderTop: `1px solid ${gray[4]}`,
    },
    '.cm-placeholder': {
      color: gray[6],
    },
    '.cm-button': {
      ...style['.btn'].attributes,
      ...style['.btn-sm, .btn-group-sm > .btn'].attributes,
      ...style['.btn-outline-secondary'].attributes,
      backgroundImage: 'none',
      '&:hover': {
        ...style['.btn:hover'].attributes,
      },
      // '&:active': {
      //   backgroundImage: 'none',
      //   ...style[
      //     '.btn-check:checked + .btn, .btn-check:active + .btn, .btn:active, .btn.active, .btn.show'
      //   ].attributes,
      // },
    },
    '.cm-textfield': {
      ...input,
      ...style['.form-control-sm'].attributes,
      '&:focus': {
        ...formFocused,
      },
    },
    // '.cm-panel.cm-search input[type=checkbox]': {
    //   ...style['.form-check-input'].attributes,
    //   ...style['.form-check-input[type=checkbox]'].attributes,
    //   '&:active': {
    //     ...style['.form-check-input:active'].attributes,
    //   },
    //   '&:checked': {
    //     ...style['.form-check-input:checked'].attributes,
    //     ...style['.form-check-input:checked[type=checkbox]'].attributes,
    //   },
    //   '&:focus': {
    //     ...style['.form-check-input:focus'].attributes,
    //   },
    // },
    '.cm-panels-top .cm-panel': {
      borderTopLeftRadius: root['--bs-border-radius'],
      borderTopRightRadius: root['--bs-border-radius'],
    },
    '.cm-panels-bottom .cm-panel': {
      borderBottomLeftRadius: root['--bs-border-radius'],
      borderBottomRightRadius: root['--bs-border-radius'],
    },
  },
  { dark: false }
);

/** Bootstrap Theme Dark mode */
export const bootstrapThemeDark: Extension = EditorView.theme(
  {
    '&.cm-editor': {
      ...form,
      '&.cm-focused': {
        ...formFocused,
      },
    },
    '.cm-scroller': {
      fontFamily: root['--bs-font-monospace'],
      lineHeight: root['--bs-body-line-height'],
    },
    '.cm-content': { caretColor: white },
    '.cm-selectionBackground': {
      background: dark,
    },
    '.cm-focused .cm-selectionBackground': {
      background: root['--bs-light'],
    },
    '.cm-cursor, .cm-dropCursor': {
      borderLeft: `1.2px solid ${gray[8]}`,
    },
    '.cm-cursor': {
      borderLeftColor: gray[7],
    },
    '.cm-activeLine': {
      color: white,
      backgroundColor: gray[6],
    },
    '.cm-specialChar': { color: red },
    '.cm-gutters': {
      backgroundColor: gray[7],
      color: gray[4],
      borderTopLeftRadius: root['--bs-border-radius'],
      borderBottomLeftRadius: root['--bs-border-radius'],
    },
    '.cm-activeLineGutter': {
      backgroundColor: dark,
    },
    '.cm-panels': {
      backgroundColor: gray[8],
      color: white,
    },
    '.cm-placeholder': {
      color: gray[6],
    },
    '.cm-button': {
      ...style['.btn'].attributes,
      ...style['.btn-sm, .btn-group-sm > .btn'].attributes,
      ...style['.btn-secondary'].attributes,
      backgroundImage: 'none',
      '&:hover': {
        ...style['.btn:hover'].attributes,
      },
      // '&:active': {
      //   backgroundImage: 'none',
      //   ...style[
      //     '.btn-check:checked + .btn, .btn-check:active + .btn, .btn:active, .btn.active, .btn.show'
      //   ].attributes,
      // },
    },
    '.cm-textfield': {
      ...input,
      ...style['.form-control-sm'].attributes,
      '&:focus': {
        ...formFocused,
      },
    },
    // '.cm-panel.cm-search input[type=checkbox]': {
    //   ...style['.form-check-input'].attributes,
    //   ...style['.form-check-input[type=checkbox]'].attributes,
    //   '&:active': {
    //     ...style['.form-check-input:active'].attributes,
    //   },
    //   '&:checked': {
    //     ...style['.form-check-input:checked'].attributes,
    //     ...style['.form-check-input:checked[type=checkbox]'].attributes,
    //   },
    //   '&:focus': {
    //     ...style['.form-check-input:focus'].attributes,
    //   },
    // },
    '.cm-panels-top .cm-panel': {
      borderTopLeftRadius: root['--bs-border-radius'],
      borderTopRightRadius: root['--bs-border-radius'],
    },
    '.cm-panels-bottom .cm-panel': {
      borderBottomLeftRadius: root['--bs-border-radius'],
      borderBottomRightRadius: root['--bs-border-radius'],
    },
  },
  { dark: true }
);

/** Bootstrap Hilighting Text Style */
export const bootstrapHighlightStyle = HighlightStyle.define([
  { tag: t.keyword, color: purple },
  {
    tag: [t.name, t.deleted, t.character, t.propertyName, t.macroName],
    color: pink,
  },
  { tag: [t.function(t.variableName), t.labelName], color: blue },
  { tag: [t.color, t.constant(t.name), t.standard(t.name)], color: orange },
  { tag: [t.definition(t.name), t.separator], color: indigo },
  {
    tag: [
      t.typeName,
      t.className,
      t.number,
      t.changed,
      t.annotation,
      t.modifier,
      t.self,
      t.namespace,
    ],
    color: yellow,
  },
  {
    tag: [
      t.operator,
      t.operatorKeyword,
      t.url,
      t.escape,
      t.regexp,
      t.link,
      t.special(t.string),
    ],
    color: cyan,
  },
  { tag: [t.meta, t.comment], color: green },
  { tag: t.strong, fontWeight: 'bold' },
  { tag: t.emphasis, fontStyle: 'italic' },
  { tag: t.strikethrough, textDecoration: 'line-through' },
  { tag: t.link, color: green, textDecoration: 'underline' },
  { tag: t.heading, fontWeight: 'bold', color: pink },
  { tag: [t.atom, t.bool, t.special(t.variableName)], color: orange },
  { tag: [t.processingInstruction, t.string, t.inserted], color: teal },
  { tag: t.invalid, color: red },
]);

export const Bootstrap: Extension = [
  bootstrapTheme,
  syntaxHighlighting(bootstrapHighlightStyle),
];

export const BootstrapDark: Extension = [
  bootstrapThemeDark,
  syntaxHighlighting(bootstrapHighlightStyle),
];

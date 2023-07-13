/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/block-filters/columns.js":
/*!*****************************************!*\
  !*** ./src/js/block-filters/columns.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_6__);









/*
    This modifies the block settings for the core/columns block
    We enable support for className and define two additional state
    attributes that will be used in the InspectorControls
*/
function modifyColumnsBlockSettings(settings, name) {
  if (name !== 'core/columns') {
    return settings;
  }
  return (0,lodash__WEBPACK_IMPORTED_MODULE_6__.assign)({}, settings, {
    supports: (0,lodash__WEBPACK_IMPORTED_MODULE_6__.merge)(settings.supports, {
      className: true
    }),
    attributes: (0,lodash__WEBPACK_IMPORTED_MODULE_6__.merge)(settings.attributes, {
      stackedBreakpoint: {
        type: 'string',
        default: 'md'
      },
      isReversedWhenStacked: {
        type: 'boolean',
        default: false
      }
    })
  });
}

/*
    This adds a slider to the Inspector Controls for the core/columns block
    and tracks the state / updates the block when changed
*/
const addInspectorControls = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__.createHigherOrderComponent)(BlockEdit => {
  return props => {
    const {
      attributes: {
        stackedBreakpoint,
        isReversedWhenStacked
      },
      setAttributes,
      name
    } = props;
    if (name !== 'core/columns') {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(BlockEdit, {
        ...props
      });
    }
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(BlockEdit, {
      ...props
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.__experimentalToggleGroupControl, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Stack on', 'pulsar'),
      value: stackedBreakpoint,
      onChange: value => setAttributes({
        stackedBreakpoint: value
      }),
      isBlock: true,
      help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Set the breakpoint size where you wish to stack the columns.', 'pulsar')
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.__experimentalToggleGroupControlOption, {
      value: "sm",
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Small', 'pulsar')
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.__experimentalToggleGroupControlOption, {
      value: "md",
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Medium', 'pulsar')
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.__experimentalToggleGroupControlOption, {
      value: "lg",
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Large', 'pulsar')
    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ToggleControl, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Reverse when stacked', 'pulsar'),
      help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Allows column order to be reversed when stacked. Useful for example if you have an image in the right column, but want it to be on top when stacked.'),
      checked: isReversedWhenStacked,
      onChange: () => setAttributes({
        isReversedWhenStacked: !isReversedWhenStacked
      })
    }))));
  };
}, 'withInspectorControl');

/**
 * Adds classes to the core/columns block in the editor.
 */
const addEditorClasses = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__.createHigherOrderComponent)(BlockListBlock => {
  return props => {
    const {
      attributes: {
        stackedBreakpoint,
        reverseOnStacked
      },
      name
    } = props;
    if (name !== 'core/columns') {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(BlockListBlock, {
        ...props
      });
    }
    const classes = [];
    if (stackedBreakpoint) {
      classes.push(`is-stacked-on-${stackedBreakpoint}`);
    }
    if (reverseOnStacked) {
      classes.push('is-reversed-on-stacked');
    }
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(BlockListBlock, {
      ...props,
      className: classes.join(' ')
    });
  };
}, 'withClientIdClassName');

/**
 * Add classes to the frontend.
 */
function addFrontendClasses(props, block, attributes) {
  if (block.name !== 'core/columns') {
    return props;
  }
  const classes = [props.className];
  const {
    stackedBreakpoint,
    isReversedWhenStacked
  } = attributes;
  classes.push(`is-stacked-on-${stackedBreakpoint}`);
  if (isReversedWhenStacked) {
    classes.push('is-reversed-when-stacked');
  }
  return (0,lodash__WEBPACK_IMPORTED_MODULE_6__.assign)({}, props, {
    className: classes.join(' ')
  });
}
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_4__.addFilter)('blocks.registerBlockType', 'pulsar/columns-block/block-settings', modifyColumnsBlockSettings);
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_4__.addFilter)('editor.BlockEdit', 'pulsar/columns-block/add-inspector-controls', addInspectorControls);
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_4__.addFilter)('editor.BlockListBlock', 'pulsar/columns-block/add-editor-classes', addEditorClasses);
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_4__.addFilter)('blocks.getSaveContent.extraProps', 'pulsar/columns-block/add-frontend-classes', addFrontendClasses);

/***/ }),

/***/ "./src/js/block-filters/heading.js":
/*!*****************************************!*\
  !*** ./src/js/block-filters/heading.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);



/**
 * Adds a default class to the heading block.
 * This may not be needed if the following is merged:
 *
 * @see https://github.com/WordPress/gutenberg/pull/42269
 *
 * @param {Object} settings The block settings
 * @param {string} name     The block name
 * @return {Object} The block settings
 */
function addHeadingBlockClassName(settings, name) {
  if (name !== 'core/heading') {
    return settings;
  }
  return (0,lodash__WEBPACK_IMPORTED_MODULE_1__.assign)({}, settings, {
    supports: (0,lodash__WEBPACK_IMPORTED_MODULE_1__.assign)({}, settings.supports, {
      className: true
    })
  });
}
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__.addFilter)('blocks.registerBlockType', 'pulsar/heading-block/class-names', addHeadingBlockClassName);

/***/ }),

/***/ "./src/js/block-filters/index.js":
/*!***************************************!*\
  !*** ./src/js/block-filters/index.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _columns__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./columns */ "./src/js/block-filters/columns.js");
/* harmony import */ var _heading__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./heading */ "./src/js/block-filters/heading.js");
/* harmony import */ var _list__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./list */ "./src/js/block-filters/list.js");
/* harmony import */ var _paragraph__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./paragraph */ "./src/js/block-filters/paragraph.js");
/**
 * Entry point for block filters.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/
 */

// Each block that needs filters to be applied, should have it's own file.





/***/ }),

/***/ "./src/js/block-filters/list.js":
/*!**************************************!*\
  !*** ./src/js/block-filters/list.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);



/**
 * Adds a default class to the list block.
 * This may not be needed if the following is merged:
 *
 * @see https://github.com/WordPress/gutenberg/pull/42269
 *
 * @param {Object} settings The block settings
 * @param {string} name     The block name
 * @return {Object} The block settings
 */
function addListBlockClassName(settings, name) {
  if (name !== 'core/list') {
    return settings;
  }
  return (0,lodash__WEBPACK_IMPORTED_MODULE_1__.assign)({}, settings, {
    supports: (0,lodash__WEBPACK_IMPORTED_MODULE_1__.assign)({}, settings.supports, {
      className: true
    })
  });
}
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__.addFilter)('blocks.registerBlockType', 'pulsar/list-block/class-names', addListBlockClassName);

/***/ }),

/***/ "./src/js/block-filters/paragraph.js":
/*!*******************************************!*\
  !*** ./src/js/block-filters/paragraph.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);



/**
 * Adds a default class to the paragraph block.
 * This may not be needed if the following is merged:
 *
 * @see https://github.com/WordPress/gutenberg/pull/42269
 *
 * @param {Object} settings The block settings
 * @param {string} name     The block name
 * @return {Object} The block settings
 */
function addParagraphBlockClassName(settings, name) {
  if (name !== 'core/paragraph') {
    return settings;
  }
  return (0,lodash__WEBPACK_IMPORTED_MODULE_1__.assign)({}, settings, {
    supports: (0,lodash__WEBPACK_IMPORTED_MODULE_1__.assign)({}, settings.supports, {
      className: true
    })
  });
}
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__.addFilter)('blocks.registerBlockType', 'pulsar/paragraph-block/class-names', addParagraphBlockClassName);

/***/ }),

/***/ "./src/js/block-styles/index.js":
/*!**************************************!*\
  !*** ./src/js/block-styles/index.js ***!
  \**************************************/
/***/ (() => {

/**
 * Entry point for block styles.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/
 */

// Each block that needs custom styles should have it's own file.
// import './button';

/***/ }),

/***/ "./src/js/block-variations/index.js":
/*!******************************************!*\
  !*** ./src/js/block-variations/index.js ***!
  \******************************************/
/***/ (() => {

/**
 * Entry point for block variations.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-variations/
 */

// Each block that needs variations should have it's own file.
// import './button';

/***/ }),

/***/ "./src/js/editor-plugins/index.js":
/*!****************************************!*\
  !*** ./src/js/editor-plugins/index.js ***!
  \****************************************/
/***/ (() => {

/**
 * Entry point for editor plugins.
 */

// Each plugin should have it's own file.

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/***/ ((module) => {

"use strict";
module.exports = window["lodash"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["compose"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/hooks":
/*!*******************************!*\
  !*** external ["wp","hooks"] ***!
  \*******************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["hooks"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!**************************!*\
  !*** ./src/js/editor.js ***!
  \**************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _block_filters__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./block-filters */ "./src/js/block-filters/index.js");
/* harmony import */ var _block_styles__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./block-styles */ "./src/js/block-styles/index.js");
/* harmony import */ var _block_styles__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_block_styles__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _block_variations__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block-variations */ "./src/js/block-variations/index.js");
/* harmony import */ var _block_variations__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_block_variations__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _editor_plugins__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor-plugins */ "./src/js/editor-plugins/index.js");
/* harmony import */ var _editor_plugins__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_editor_plugins__WEBPACK_IMPORTED_MODULE_3__);
/**
 * Entry point for all core block overrides.
 */




})();

/******/ })()
;
//# sourceMappingURL=editor-scripts.js.map
/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/block-filters/columns.js":
/*!*****************************************!*\
  !*** ./src/js/block-filters/columns.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_token_list__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/token-list */ "@wordpress/token-list");
/* harmony import */ var _wordpress_token_list__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_token_list__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);

// WordPress dependencies.






const withMultiBreakpoint = BlockEdit => props => {
  const {
    attributes,
    setAttributes
  } = props;
  const {
    className
  } = attributes;

  // Define our breakpoints.
  const breakpoints = ['sm', 'md', 'lg', 'xl'];

  // Define the classes used for setting stacking.
  const classes = {
    reversed: 'is-reversed-when-stacked',
    ...breakpoints.reduce((acc, breakpoint) => {
      acc[breakpoint] = `is-stacked-on-${breakpoint}`;
      return acc;
    }, {})
  };

  // Set state for the active breakpoint.
  const [activeBreakpoint, setActiveBreakpoint] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);

  // Define useful helper text for each breakpoint.
  const helpText = {
    sm: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Mobile screens.'),
    md: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Landscape mobiles and below.'),
    lg: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Tablets in portrait mode and below.'),
    xl: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Smaller laptops or tablets in landscape mode and below.')
  };

  // Fetch the helper text for a breakpoint.
  const getHelpText = key => helpText[key];

  // Toggles a breakpoint class, and removes any other existing ones.
  const toggleBreakpointClass = (toggleClass, enable) => {
    const list = new (_wordpress_token_list__WEBPACK_IMPORTED_MODULE_4___default())(className);

    // Remove all classes except the one being toggled
    breakpoints.forEach(breakpoint => {
      if (breakpoint !== toggleClass) {
        list.remove(classes[breakpoint]);
      }
    });
    if (enable) {
      list.add(toggleClass);
    }
    setAttributes({
      className: list.value
    });
  };

  // Toggle the reversed class independently.
  const toggleReversedClass = enable => {
    const list = new (_wordpress_token_list__WEBPACK_IMPORTED_MODULE_4___default())(className);
    if (enable) {
      list.add(classes.reversed);
    } else {
      list.remove(classes.reversed);
    }
    setAttributes({
      className: list.value
    });
  };

  // Remove all our custom classes.
  // Used when stack on mobile is turned off.
  const removeAllClasses = () => {
    const list = new (_wordpress_token_list__WEBPACK_IMPORTED_MODULE_4___default())(className);
    for (const size in classes) {
      list.remove(classes[size]);
    }
    setAttributes({
      className: list.value
    });
  };

  // Check if the columns have a particular class.
  const hasClass = name => {
    return attributes.className?.includes(name);
  };

  // Set the initial columns state, along with clearing classes when stacking is disabled.
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    if (attributes.isStackedOnMobile) {
      const activeClass = breakpoints.find(breakpoint => hasClass(classes[breakpoint]));
      if (activeClass) {
        setActiveBreakpoint(activeClass);
      } else {
        setActiveBreakpoint('sm');
        toggleBreakpointClass(classes.sm, true);
      }
    } else {
      removeAllClasses();
      setActiveBreakpoint(false);
    }
  }, [attributes.isStackedOnMobile]); //eslint-disable-line react-hooks/exhaustive-deps

  return 'core/columns' === props.name ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(BlockEdit, {
    ...props
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, null, attributes.isStackedOnMobile && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalToggleGroupControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Screen sizes up to'),
    onChange: value => {
      setActiveBreakpoint(value);
      toggleBreakpointClass(classes[value], value);
    },
    value: activeBreakpoint,
    isBlock: true,
    help: getHelpText(activeBreakpoint)
  }, breakpoints.map(breakpoint => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.__experimentalToggleGroupControlOption, {
    key: breakpoint,
    value: breakpoint,
    label: breakpoint.toUpperCase()
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Reversed when stacked'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__)('Reverse columns when stacked.'),
    checked: hasClass(classes.reversed),
    onChange: val => {
      toggleReversedClass(val);
    }
  })))) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(BlockEdit, {
    ...props
  });
};
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_3__.addFilter)('editor.BlockEdit', 'pulsar/columns-block', withMultiBreakpoint);

/***/ }),

/***/ "./src/js/block-filters/index.js":
/*!***************************************!*\
  !*** ./src/js/block-filters/index.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _columns__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./columns */ "./src/js/block-filters/columns.js");
/**
 * Entry point for block filters.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/
 */

// Each block that needs filters to be applied, should have it's own file.


/***/ }),

/***/ "./src/js/block-styles/button.js":
/*!***************************************!*\
  !*** ./src/js/block-styles/button.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/dom-ready */ "@wordpress/dom-ready");
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/**
 * This file serves as an example of how to register and unregister block styles.
 */


_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0___default()(() => {
  (0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.unregisterBlockStyle)('core/button', 'outline');
});

/***/ }),

/***/ "./src/js/block-styles/image.js":
/*!**************************************!*\
  !*** ./src/js/block-styles/image.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/dom-ready */ "@wordpress/dom-ready");
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/**
 * This file serves as an example of how to register and unregister block styles.
 */


_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0___default()(() => {
  (0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.unregisterBlockStyle)('core/image', 'rounded');
});

/***/ }),

/***/ "./src/js/block-styles/index.js":
/*!**************************************!*\
  !*** ./src/js/block-styles/index.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _button__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./button */ "./src/js/block-styles/button.js");
/* harmony import */ var _quote__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./quote */ "./src/js/block-styles/quote.js");
/* harmony import */ var _image__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./image */ "./src/js/block-styles/image.js");
/**
 * Entry point for block styles.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/
 */

// Each block that needs custom styles should have it's own file.




/***/ }),

/***/ "./src/js/block-styles/quote.js":
/*!**************************************!*\
  !*** ./src/js/block-styles/quote.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/dom-ready */ "@wordpress/dom-ready");
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/**
 * This file serves as an example of how to register and unregister block styles.
 */


_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0___default()(() => {
  (0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.unregisterBlockStyle)('core/quote', 'plain');
});

/***/ }),

/***/ "./src/js/block-variations/embed.js":
/*!******************************************!*\
  !*** ./src/js/block-variations/embed.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/dom-ready */ "@wordpress/dom-ready");
/* harmony import */ var _wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);



// For a full list of embeds, see this this link:
// https://wordpress.org/documentation/category/embed-blocks/
const availableEmbeds = ['vimeo', 'youtube'];
_wordpress_dom_ready__WEBPACK_IMPORTED_MODULE_0___default()(() => {
  const embedVariations = (0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.getBlockVariations)('core/embed');
  embedVariations.forEach(blockVariation => {
    if (!availableEmbeds.includes(blockVariation.name)) {
      (0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__.unregisterBlockVariation)('core/embed', blockVariation.name);
    }
  });
});

/***/ }),

/***/ "./src/js/block-variations/index.js":
/*!******************************************!*\
  !*** ./src/js/block-variations/index.js ***!
  \******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _embed__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./embed */ "./src/js/block-variations/embed.js");
/**
 * Entry point for block variations.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-variations/
 */

// Each block that needs variations should have it's own file.


/***/ }),

/***/ "./src/js/editor-plugins/index.js":
/*!****************************************!*\
  !*** ./src/js/editor-plugins/index.js ***!
  \****************************************/
/***/ (function() {

/**
 * Entry point for editor plugins.
 */

// Each plugin should have it's own file.

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/dom-ready":
/*!**********************************!*\
  !*** external ["wp","domReady"] ***!
  \**********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["domReady"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/hooks":
/*!*******************************!*\
  !*** external ["wp","hooks"] ***!
  \*******************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["hooks"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/token-list":
/*!***********************************!*\
  !*** external ["wp","tokenList"] ***!
  \***********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["tokenList"];

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
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";
/*!**************************!*\
  !*** ./src/js/editor.js ***!
  \**************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _block_filters__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./block-filters */ "./src/js/block-filters/index.js");
/* harmony import */ var _block_styles__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./block-styles */ "./src/js/block-styles/index.js");
/* harmony import */ var _block_variations__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block-variations */ "./src/js/block-variations/index.js");
/* harmony import */ var _editor_plugins__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor-plugins */ "./src/js/editor-plugins/index.js");
/* harmony import */ var _editor_plugins__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_editor_plugins__WEBPACK_IMPORTED_MODULE_3__);
/**
 * Entry point for all core block overrides.
 */




}();
/******/ })()
;
//# sourceMappingURL=editor.js.map
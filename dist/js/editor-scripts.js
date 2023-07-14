(()=>{"use strict";const e=window.wp.element,t=window.wp.compose,o=window.wp.blockEditor,n=window.wp.components,r=window.wp.hooks,s=window.wp.i18n,l=window.lodash,a=(0,t.createHigherOrderComponent)((t=>r=>{const{attributes:{stackedBreakpoint:l,isReversedWhenStacked:a},setAttributes:c,name:i}=r;return"core/columns"!==i?(0,e.createElement)(t,{...r}):(0,e.createElement)(e.Fragment,null,(0,e.createElement)(t,{...r}),(0,e.createElement)(o.InspectorControls,null,(0,e.createElement)(n.PanelBody,null,(0,e.createElement)(n.__experimentalToggleGroupControl,{label:(0,s.__)("Stack on","pulsar"),value:l,onChange:e=>c({stackedBreakpoint:e}),isBlock:!0,help:(0,s.__)("Set the breakpoint size where you wish to stack the columns.","pulsar")},(0,e.createElement)(n.__experimentalToggleGroupControlOption,{value:"sm",label:(0,s.__)("Small","pulsar")}),(0,e.createElement)(n.__experimentalToggleGroupControlOption,{value:"md",label:(0,s.__)("Medium","pulsar")}),(0,e.createElement)(n.__experimentalToggleGroupControlOption,{value:"lg",label:(0,s.__)("Large","pulsar")})),(0,e.createElement)(n.ToggleControl,{label:(0,s.__)("Reverse when stacked","pulsar"),help:(0,s.__)("Allows column order to be reversed when stacked. Useful for example if you have an image in the right column, but want it to be on top when stacked."),checked:a,onChange:()=>c({isReversedWhenStacked:!a})}))))}),"withInspectorControl"),c=(0,t.createHigherOrderComponent)((t=>o=>{const{attributes:{stackedBreakpoint:n,reverseOnStacked:r},name:s}=o;if("core/columns"!==s)return(0,e.createElement)(t,{...o});const l=[];return n&&l.push(`is-stacked-on-${n}`),r&&l.push("is-reversed-on-stacked"),(0,e.createElement)(t,{...o,className:l.join(" ")})}),"withClientIdClassName");(0,r.addFilter)("blocks.registerBlockType","pulsar/columns-block/block-settings",(function(e,t){return"core/columns"!==t?e:(0,l.assign)({},e,{supports:(0,l.merge)(e.supports,{className:!0}),attributes:(0,l.merge)(e.attributes,{stackedBreakpoint:{type:"string",default:"md"},isReversedWhenStacked:{type:"boolean",default:!1}})})})),(0,r.addFilter)("editor.BlockEdit","pulsar/columns-block/add-inspector-controls",a),(0,r.addFilter)("editor.BlockListBlock","pulsar/columns-block/add-editor-classes",c),(0,r.addFilter)("blocks.getSaveContent.extraProps","pulsar/columns-block/add-frontend-classes",(function(e,t,o){if("core/columns"!==t.name)return e;const n=[e.className],{stackedBreakpoint:r,isReversedWhenStacked:s}=o;return n.push(`is-stacked-on-${r}`),s&&n.push("is-reversed-when-stacked"),(0,l.assign)({},e,{className:n.join(" ")})}))})();
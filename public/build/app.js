"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/app.js"
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.array.concat.js */ "./node_modules/core-js/modules/es.array.concat.js");
/* harmony import */ var core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_array_for_each_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.array.for-each.js */ "./node_modules/core-js/modules/es.array.for-each.js");
/* harmony import */ var core_js_modules_es_array_for_each_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_for_each_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_array_includes_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.array.includes.js */ "./node_modules/core-js/modules/es.array.includes.js");
/* harmony import */ var core_js_modules_es_array_includes_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_includes_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var core_js_modules_es_parse_int_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! core-js/modules/es.parse-int.js */ "./node_modules/core-js/modules/es.parse-int.js");
/* harmony import */ var core_js_modules_es_parse_int_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_parse_int_js__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var core_js_modules_esnext_iterator_constructor_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! core-js/modules/esnext.iterator.constructor.js */ "./node_modules/core-js/modules/esnext.iterator.constructor.js");
/* harmony import */ var core_js_modules_esnext_iterator_constructor_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_esnext_iterator_constructor_js__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var core_js_modules_esnext_iterator_for_each_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! core-js/modules/esnext.iterator.for-each.js */ "./node_modules/core-js/modules/esnext.iterator.for-each.js");
/* harmony import */ var core_js_modules_esnext_iterator_for_each_js__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_esnext_iterator_for_each_js__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! core-js/modules/web.dom-collections.for-each.js */ "./node_modules/core-js/modules/web.dom-collections.for-each.js");
/* harmony import */ var core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _styles_app_scss__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./styles/app.scss */ "./assets/styles/app.scss");









document.addEventListener('DOMContentLoaded', function () {
  var _document$getElementB, _document$getElementB2;
  // ── Burger menu toggle ──
  var burger = document.querySelector('.navbar__burger');
  var collapse = document.querySelector('.navbar__collapse');
  if (burger && collapse) {
    burger.addEventListener('click', function () {
      var isOpen = collapse.classList.toggle('navbar__collapse--open');
      burger.classList.toggle('navbar__burger--open', isOpen);
      burger.setAttribute('aria-expanded', isOpen);
    });
  }

  // ── Rating sliders ──
  document.querySelectorAll('.rating-slider').forEach(function (slider) {
    var isOptional = slider.dataset.optional === 'true';
    var wrapper = document.createElement('div');
    wrapper.className = 'rating-slider-wrapper';
    slider.parentNode.insertBefore(wrapper, slider);
    wrapper.appendChild(slider);
    var display = document.createElement('span');
    display.className = 'rating-slider__value';
    wrapper.appendChild(display);
    var update = function update() {
      var val = parseInt(slider.value, 10);
      if (isOptional && val === 0) {
        display.textContent = '—';
        display.classList.add('rating-slider__value--none');
        // update track fill to grey
        slider.style.background = 'rgba(255,255,255,0.1)';
      } else {
        display.textContent = val + '/10';
        display.classList.remove('rating-slider__value--none');
        var pct = (val - parseInt(slider.min, 10)) / (parseInt(slider.max, 10) - parseInt(slider.min, 10)) * 100;
        slider.style.background = "linear-gradient(to right, #e94560 ".concat(pct, "%, rgba(255,255,255,0.1) ").concat(pct, "%)");
      }
    };
    slider.addEventListener('input', update);
    update();
  });

  // ── Toggle progression field visibility based on game status ──
  var statutSelect = document.getElementById('user_game_collection_statut');
  if (!statutSelect) return;
  var progressionRow = ((_document$getElementB = document.getElementById('user_game_collection_progression')) === null || _document$getElementB === void 0 ? void 0 : _document$getElementB.closest('.form-group')) || ((_document$getElementB2 = document.getElementById('user_game_collection_progression')) === null || _document$getElementB2 === void 0 ? void 0 : _document$getElementB2.parentElement);
  if (!progressionRow) return;
  var toggleProgression = function toggleProgression() {
    var show = ['en_cours', 'en_pause'].includes(statutSelect.value);
    progressionRow.style.display = show ? '' : 'none';
  };
  toggleProgression();
  statutSelect.addEventListener('change', toggleProgression);
});

/***/ },

/***/ "./assets/styles/app.scss"
/*!********************************!*\
  !*** ./assets/styles/app.scss ***!
  \********************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_core-js_modules_es_array_concat_js-node_modules_core-js_modules_es_array-6c79e7"], () => (__webpack_exec__("./assets/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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Qzs7RUFFRjtFQUNBLElBQU1hLFlBQVksR0FBR3JDLFFBQVEsQ0FBQ3NDLGNBQWMsQ0FBQyw2QkFBNkIsQ0FBQztFQUMzRSxJQUFJLENBQUNELFlBQVksRUFBRTtFQUVuQixJQUFNRSxjQUFjLEdBQUcsRUFBQXJDLHFCQUFBLEdBQUFGLFFBQVEsQ0FBQ3NDLGNBQWMsQ0FBQyxrQ0FBa0MsQ0FBQyxjQUFBcEMscUJBQUEsdUJBQTNEQSxxQkFBQSxDQUE2RHNDLE9BQU8sQ0FBQyxhQUFhLENBQUMsT0FBQXJDLHNCQUFBLEdBQ25HSCxRQUFRLENBQUNzQyxjQUFjLENBQUMsa0NBQWtDLENBQUMsY0FBQW5DLHNCQUFBLHVCQUEzREEsc0JBQUEsQ0FBNkRzQyxhQUFhO0VBQ2pGLElBQUksQ0FBQ0YsY0FBYyxFQUFFO0VBRXJCLElBQU1HLGlCQUFpQixHQUFHLFNBQXBCQSxpQkFBaUJBLENBQUEsRUFBUztJQUM1QixJQUFNQyxJQUFJLEdBQUcsQ0FBQyxVQUFVLEVBQUUsVUFBVSxDQUFDLENBQUNDLFFBQVEsQ0FBQ1AsWUFBWSxDQUFDVixLQUFLLENBQUM7SUFDbEVZLGNBQWMsQ0FBQ1QsS0FBSyxDQUFDUCxPQUFPLEdBQUdvQixJQUFJLEdBQUcsRUFBRSxHQUFHLE1BQU07RUFDckQsQ0FBQztFQUVERCxpQkFBaUIsQ0FBQyxDQUFDO0VBQ25CTCxZQUFZLENBQUNwQyxnQkFBZ0IsQ0FBQyxRQUFRLEVBQUV5QyxpQkFBaUIsQ0FBQztBQUM5RCxDQUFDLENBQUMsQzs7Ozs7Ozs7Ozs7QUM3REYiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvYXBwLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9zdHlsZXMvYXBwLnNjc3M/OGY1OSJdLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgJy4vc3R5bGVzL2FwcC5zY3NzJztcclxuXHJcbmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCAoKSA9PiB7XHJcbiAgICAvLyDilIDilIAgQnVyZ2VyIG1lbnUgdG9nZ2xlIOKUgOKUgFxyXG4gICAgY29uc3QgYnVyZ2VyID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLm5hdmJhcl9fYnVyZ2VyJyk7XHJcbiAgICBjb25zdCBjb2xsYXBzZSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5uYXZiYXJfX2NvbGxhcHNlJyk7XHJcbiAgICBpZiAoYnVyZ2VyICYmIGNvbGxhcHNlKSB7XHJcbiAgICAgICAgYnVyZ2VyLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgKCkgPT4ge1xyXG4gICAgICAgICAgICBjb25zdCBpc09wZW4gPSBjb2xsYXBzZS5jbGFzc0xpc3QudG9nZ2xlKCduYXZiYXJfX2NvbGxhcHNlLS1vcGVuJyk7XHJcbiAgICAgICAgICAgIGJ1cmdlci5jbGFzc0xpc3QudG9nZ2xlKCduYXZiYXJfX2J1cmdlci0tb3BlbicsIGlzT3Blbik7XHJcbiAgICAgICAgICAgIGJ1cmdlci5zZXRBdHRyaWJ1dGUoJ2FyaWEtZXhwYW5kZWQnLCBpc09wZW4pO1xyXG4gICAgICAgIH0pO1xyXG4gICAgfVxyXG5cclxuICAgIC8vIOKUgOKUgCBSYXRpbmcgc2xpZGVycyDilIDilIBcclxuICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5yYXRpbmctc2xpZGVyJykuZm9yRWFjaChzbGlkZXIgPT4ge1xyXG4gICAgICAgIGNvbnN0IGlzT3B0aW9uYWwgPSBzbGlkZXIuZGF0YXNldC5vcHRpb25hbCA9PT0gJ3RydWUnO1xyXG5cclxuICAgICAgICBjb25zdCB3cmFwcGVyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XHJcbiAgICAgICAgd3JhcHBlci5jbGFzc05hbWUgPSAncmF0aW5nLXNsaWRlci13cmFwcGVyJztcclxuICAgICAgICBzbGlkZXIucGFyZW50Tm9kZS5pbnNlcnRCZWZvcmUod3JhcHBlciwgc2xpZGVyKTtcclxuICAgICAgICB3cmFwcGVyLmFwcGVuZENoaWxkKHNsaWRlcik7XHJcblxyXG4gICAgICAgIGNvbnN0IGRpc3BsYXkgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XHJcbiAgICAgICAgZGlzcGxheS5jbGFzc05hbWUgPSAncmF0aW5nLXNsaWRlcl9fdmFsdWUnO1xyXG4gICAgICAgIHdyYXBwZXIuYXBwZW5kQ2hpbGQoZGlzcGxheSk7XHJcblxyXG4gICAgICAgIGNvbnN0IHVwZGF0ZSA9ICgpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgdmFsID0gcGFyc2VJbnQoc2xpZGVyLnZhbHVlLCAxMCk7XHJcbiAgICAgICAgICAgIGlmIChpc09wdGlvbmFsICYmIHZhbCA9PT0gMCkge1xyXG4gICAgICAgICAgICAgICAgZGlzcGxheS50ZXh0Q29udGVudCA9ICfigJQnO1xyXG4gICAgICAgICAgICAgICAgZGlzcGxheS5jbGFzc0xpc3QuYWRkKCdyYXRpbmctc2xpZGVyX192YWx1ZS0tbm9uZScpO1xyXG4gICAgICAgICAgICAgICAgLy8gdXBkYXRlIHRyYWNrIGZpbGwgdG8gZ3JleVxyXG4gICAgICAgICAgICAgICAgc2xpZGVyLnN0eWxlLmJhY2tncm91bmQgPSAncmdiYSgyNTUsMjU1LDI1NSwwLjEpJztcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIGRpc3BsYXkudGV4dENvbnRlbnQgPSB2YWwgKyAnLzEwJztcclxuICAgICAgICAgICAgICAgIGRpc3BsYXkuY2xhc3NMaXN0LnJlbW92ZSgncmF0aW5nLXNsaWRlcl9fdmFsdWUtLW5vbmUnKTtcclxuICAgICAgICAgICAgICAgIGNvbnN0IHBjdCA9ICgodmFsIC0gcGFyc2VJbnQoc2xpZGVyLm1pbiwgMTApKSAvIChwYXJzZUludChzbGlkZXIubWF4LCAxMCkgLSBwYXJzZUludChzbGlkZXIubWluLCAxMCkpKSAqIDEwMDtcclxuICAgICAgICAgICAgICAgIHNsaWRlci5zdHlsZS5iYWNrZ3JvdW5kID0gYGxpbmVhci1ncmFkaWVudCh0byByaWdodCwgI2U5NDU2MCAke3BjdH0lLCByZ2JhKDI1NSwyNTUsMjU1LDAuMSkgJHtwY3R9JSlgO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfTtcclxuXHJcbiAgICAgICAgc2xpZGVyLmFkZEV2ZW50TGlzdGVuZXIoJ2lucHV0JywgdXBkYXRlKTtcclxuICAgICAgICB1cGRhdGUoKTtcclxuICAgIH0pO1xyXG5cclxuICAgIC8vIOKUgOKUgCBUb2dnbGUgcHJvZ3Jlc3Npb24gZmllbGQgdmlzaWJpbGl0eSBiYXNlZCBvbiBnYW1lIHN0YXR1cyDilIDilIBcclxuICAgIGNvbnN0IHN0YXR1dFNlbGVjdCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd1c2VyX2dhbWVfY29sbGVjdGlvbl9zdGF0dXQnKTtcclxuICAgIGlmICghc3RhdHV0U2VsZWN0KSByZXR1cm47XHJcblxyXG4gICAgY29uc3QgcHJvZ3Jlc3Npb25Sb3cgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndXNlcl9nYW1lX2NvbGxlY3Rpb25fcHJvZ3Jlc3Npb24nKT8uY2xvc2VzdCgnLmZvcm0tZ3JvdXAnKVxyXG4gICAgICAgIHx8IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd1c2VyX2dhbWVfY29sbGVjdGlvbl9wcm9ncmVzc2lvbicpPy5wYXJlbnRFbGVtZW50O1xyXG4gICAgaWYgKCFwcm9ncmVzc2lvblJvdykgcmV0dXJuO1xyXG5cclxuICAgIGNvbnN0IHRvZ2dsZVByb2dyZXNzaW9uID0gKCkgPT4ge1xyXG4gICAgICAgIGNvbnN0IHNob3cgPSBbJ2VuX2NvdXJzJywgJ2VuX3BhdXNlJ10uaW5jbHVkZXMoc3RhdHV0U2VsZWN0LnZhbHVlKTtcclxuICAgICAgICBwcm9ncmVzc2lvblJvdy5zdHlsZS5kaXNwbGF5ID0gc2hvdyA/ICcnIDogJ25vbmUnO1xyXG4gICAgfTtcclxuXHJcbiAgICB0b2dnbGVQcm9ncmVzc2lvbigpO1xyXG4gICAgc3RhdHV0U2VsZWN0LmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIHRvZ2dsZVByb2dyZXNzaW9uKTtcclxufSk7XHJcbiIsIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpblxuZXhwb3J0IHt9OyJdLCJuYW1lcyI6WyJkb2N1bWVudCIsImFkZEV2ZW50TGlzdGVuZXIiLCJfZG9jdW1lbnQkZ2V0RWxlbWVudEIiLCJfZG9jdW1lbnQkZ2V0RWxlbWVudEIyIiwiYnVyZ2VyIiwicXVlcnlTZWxlY3RvciIsImNvbGxhcHNlIiwiaXNPcGVuIiwiY2xhc3NMaXN0IiwidG9nZ2xlIiwic2V0QXR0cmlidXRlIiwicXVlcnlTZWxlY3RvckFsbCIsImZvckVhY2giLCJzbGlkZXIiLCJpc09wdGlvbmFsIiwiZGF0YXNldCIsIm9wdGlvbmFsIiwid3JhcHBlciIsImNyZWF0ZUVsZW1lbnQiLCJjbGFzc05hbWUiLCJwYXJlbnROb2RlIiwiaW5zZXJ0QmVmb3JlIiwiYXBwZW5kQ2hpbGQiLCJkaXNwbGF5IiwidXBkYXRlIiwidmFsIiwicGFyc2VJbnQiLCJ2YWx1ZSIsInRleHRDb250ZW50IiwiYWRkIiwic3R5bGUiLCJiYWNrZ3JvdW5kIiwicmVtb3ZlIiwicGN0IiwibWluIiwibWF4IiwiY29uY2F0Iiwic3RhdHV0U2VsZWN0IiwiZ2V0RWxlbWVudEJ5SWQiLCJwcm9ncmVzc2lvblJvdyIsImNsb3Nlc3QiLCJwYXJlbnRFbGVtZW50IiwidG9nZ2xlUHJvZ3Jlc3Npb24iLCJzaG93IiwiaW5jbHVkZXMiXSwic291cmNlUm9vdCI6IiJ9
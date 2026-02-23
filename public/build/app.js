"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/app.js"
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_array_includes_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.array.includes.js */ "./node_modules/core-js/modules/es.array.includes.js");
/* harmony import */ var core_js_modules_es_array_includes_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_includes_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _styles_app_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./styles/app.scss */ "./assets/styles/app.scss");



// Toggle progression field visibility based on game status
document.addEventListener('DOMContentLoaded', function () {
  var _document$getElementB, _document$getElementB2;
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
/******/ __webpack_require__.O(0, ["vendors-node_modules_core-js_modules_es_array_includes_js"], () => (__webpack_exec__("./assets/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXBwLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7O0FBQTJCOztBQUUzQjtBQUNBQSxRQUFRLENBQUNDLGdCQUFnQixDQUFDLGtCQUFrQixFQUFFLFlBQU07RUFBQSxJQUFBQyxxQkFBQSxFQUFBQyxzQkFBQTtFQUNoRCxJQUFNQyxZQUFZLEdBQUdKLFFBQVEsQ0FBQ0ssY0FBYyxDQUFDLDZCQUE2QixDQUFDO0VBQzNFLElBQUksQ0FBQ0QsWUFBWSxFQUFFO0VBRW5CLElBQU1FLGNBQWMsR0FBRyxFQUFBSixxQkFBQSxHQUFBRixRQUFRLENBQUNLLGNBQWMsQ0FBQyxrQ0FBa0MsQ0FBQyxjQUFBSCxxQkFBQSx1QkFBM0RBLHFCQUFBLENBQTZESyxPQUFPLENBQUMsYUFBYSxDQUFDLE9BQUFKLHNCQUFBLEdBQ25HSCxRQUFRLENBQUNLLGNBQWMsQ0FBQyxrQ0FBa0MsQ0FBQyxjQUFBRixzQkFBQSx1QkFBM0RBLHNCQUFBLENBQTZESyxhQUFhO0VBQ2pGLElBQUksQ0FBQ0YsY0FBYyxFQUFFO0VBRXJCLElBQU1HLGlCQUFpQixHQUFHLFNBQXBCQSxpQkFBaUJBLENBQUEsRUFBUztJQUM1QixJQUFNQyxJQUFJLEdBQUcsQ0FBQyxVQUFVLEVBQUUsVUFBVSxDQUFDLENBQUNDLFFBQVEsQ0FBQ1AsWUFBWSxDQUFDUSxLQUFLLENBQUM7SUFDbEVOLGNBQWMsQ0FBQ08sS0FBSyxDQUFDQyxPQUFPLEdBQUdKLElBQUksR0FBRyxFQUFFLEdBQUcsTUFBTTtFQUNyRCxDQUFDO0VBRURELGlCQUFpQixDQUFDLENBQUM7RUFDbkJMLFlBQVksQ0FBQ0gsZ0JBQWdCLENBQUMsUUFBUSxFQUFFUSxpQkFBaUIsQ0FBQztBQUM5RCxDQUFDLENBQUMsQzs7Ozs7Ozs7Ozs7QUNsQkYiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvYXBwLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9zdHlsZXMvYXBwLnNjc3MiXSwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0ICcuL3N0eWxlcy9hcHAuc2Nzcyc7XHJcblxyXG4vLyBUb2dnbGUgcHJvZ3Jlc3Npb24gZmllbGQgdmlzaWJpbGl0eSBiYXNlZCBvbiBnYW1lIHN0YXR1c1xyXG5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgKCkgPT4ge1xyXG4gICAgY29uc3Qgc3RhdHV0U2VsZWN0ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3VzZXJfZ2FtZV9jb2xsZWN0aW9uX3N0YXR1dCcpO1xyXG4gICAgaWYgKCFzdGF0dXRTZWxlY3QpIHJldHVybjtcclxuXHJcbiAgICBjb25zdCBwcm9ncmVzc2lvblJvdyA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd1c2VyX2dhbWVfY29sbGVjdGlvbl9wcm9ncmVzc2lvbicpPy5jbG9zZXN0KCcuZm9ybS1ncm91cCcpXHJcbiAgICAgICAgfHwgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3VzZXJfZ2FtZV9jb2xsZWN0aW9uX3Byb2dyZXNzaW9uJyk/LnBhcmVudEVsZW1lbnQ7XHJcbiAgICBpZiAoIXByb2dyZXNzaW9uUm93KSByZXR1cm47XHJcblxyXG4gICAgY29uc3QgdG9nZ2xlUHJvZ3Jlc3Npb24gPSAoKSA9PiB7XHJcbiAgICAgICAgY29uc3Qgc2hvdyA9IFsnZW5fY291cnMnLCAnZW5fcGF1c2UnXS5pbmNsdWRlcyhzdGF0dXRTZWxlY3QudmFsdWUpO1xyXG4gICAgICAgIHByb2dyZXNzaW9uUm93LnN0eWxlLmRpc3BsYXkgPSBzaG93ID8gJycgOiAnbm9uZSc7XHJcbiAgICB9O1xyXG5cclxuICAgIHRvZ2dsZVByb2dyZXNzaW9uKCk7XHJcbiAgICBzdGF0dXRTZWxlY3QuYWRkRXZlbnRMaXN0ZW5lcignY2hhbmdlJywgdG9nZ2xlUHJvZ3Jlc3Npb24pO1xyXG59KTtcclxuIiwiLy8gZXh0cmFjdGVkIGJ5IG1pbmktY3NzLWV4dHJhY3QtcGx1Z2luXG5leHBvcnQge307Il0sIm5hbWVzIjpbImRvY3VtZW50IiwiYWRkRXZlbnRMaXN0ZW5lciIsIl9kb2N1bWVudCRnZXRFbGVtZW50QiIsIl9kb2N1bWVudCRnZXRFbGVtZW50QjIiLCJzdGF0dXRTZWxlY3QiLCJnZXRFbGVtZW50QnlJZCIsInByb2dyZXNzaW9uUm93IiwiY2xvc2VzdCIsInBhcmVudEVsZW1lbnQiLCJ0b2dnbGVQcm9ncmVzc2lvbiIsInNob3ciLCJpbmNsdWRlcyIsInZhbHVlIiwic3R5bGUiLCJkaXNwbGF5Il0sInNvdXJjZVJvb3QiOiIifQ==
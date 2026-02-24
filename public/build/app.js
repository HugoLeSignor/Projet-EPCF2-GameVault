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
/******/ __webpack_require__.O(0, ["vendors-node_modules_core-js_modules_es_array_includes_js"], () => (__webpack_exec__("./assets/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXBwLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7O0FBQTJCO0FBRTNCQSxRQUFRLENBQUNDLGdCQUFnQixDQUFDLGtCQUFrQixFQUFFLFlBQU07RUFBQSxJQUFBQyxxQkFBQSxFQUFBQyxzQkFBQTtFQUNoRDtFQUNBLElBQU1DLE1BQU0sR0FBR0osUUFBUSxDQUFDSyxhQUFhLENBQUMsaUJBQWlCLENBQUM7RUFDeEQsSUFBTUMsUUFBUSxHQUFHTixRQUFRLENBQUNLLGFBQWEsQ0FBQyxtQkFBbUIsQ0FBQztFQUM1RCxJQUFJRCxNQUFNLElBQUlFLFFBQVEsRUFBRTtJQUNwQkYsTUFBTSxDQUFDSCxnQkFBZ0IsQ0FBQyxPQUFPLEVBQUUsWUFBTTtNQUNuQyxJQUFNTSxNQUFNLEdBQUdELFFBQVEsQ0FBQ0UsU0FBUyxDQUFDQyxNQUFNLENBQUMsd0JBQXdCLENBQUM7TUFDbEVMLE1BQU0sQ0FBQ0ksU0FBUyxDQUFDQyxNQUFNLENBQUMsc0JBQXNCLEVBQUVGLE1BQU0sQ0FBQztNQUN2REgsTUFBTSxDQUFDTSxZQUFZLENBQUMsZUFBZSxFQUFFSCxNQUFNLENBQUM7SUFDaEQsQ0FBQyxDQUFDO0VBQ047O0VBRUE7RUFDQSxJQUFNSSxZQUFZLEdBQUdYLFFBQVEsQ0FBQ1ksY0FBYyxDQUFDLDZCQUE2QixDQUFDO0VBQzNFLElBQUksQ0FBQ0QsWUFBWSxFQUFFO0VBRW5CLElBQU1FLGNBQWMsR0FBRyxFQUFBWCxxQkFBQSxHQUFBRixRQUFRLENBQUNZLGNBQWMsQ0FBQyxrQ0FBa0MsQ0FBQyxjQUFBVixxQkFBQSx1QkFBM0RBLHFCQUFBLENBQTZEWSxPQUFPLENBQUMsYUFBYSxDQUFDLE9BQUFYLHNCQUFBLEdBQ25HSCxRQUFRLENBQUNZLGNBQWMsQ0FBQyxrQ0FBa0MsQ0FBQyxjQUFBVCxzQkFBQSx1QkFBM0RBLHNCQUFBLENBQTZEWSxhQUFhO0VBQ2pGLElBQUksQ0FBQ0YsY0FBYyxFQUFFO0VBRXJCLElBQU1HLGlCQUFpQixHQUFHLFNBQXBCQSxpQkFBaUJBLENBQUEsRUFBUztJQUM1QixJQUFNQyxJQUFJLEdBQUcsQ0FBQyxVQUFVLEVBQUUsVUFBVSxDQUFDLENBQUNDLFFBQVEsQ0FBQ1AsWUFBWSxDQUFDUSxLQUFLLENBQUM7SUFDbEVOLGNBQWMsQ0FBQ08sS0FBSyxDQUFDQyxPQUFPLEdBQUdKLElBQUksR0FBRyxFQUFFLEdBQUcsTUFBTTtFQUNyRCxDQUFDO0VBRURELGlCQUFpQixDQUFDLENBQUM7RUFDbkJMLFlBQVksQ0FBQ1YsZ0JBQWdCLENBQUMsUUFBUSxFQUFFZSxpQkFBaUIsQ0FBQztBQUM5RCxDQUFDLENBQUMsQzs7Ozs7Ozs7Ozs7QUM3QkYiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvYXBwLmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9zdHlsZXMvYXBwLnNjc3MiXSwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0ICcuL3N0eWxlcy9hcHAuc2Nzcyc7XHJcblxyXG5kb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgKCkgPT4ge1xyXG4gICAgLy8g4pSA4pSAIEJ1cmdlciBtZW51IHRvZ2dsZSDilIDilIBcclxuICAgIGNvbnN0IGJ1cmdlciA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5uYXZiYXJfX2J1cmdlcicpO1xyXG4gICAgY29uc3QgY29sbGFwc2UgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcubmF2YmFyX19jb2xsYXBzZScpO1xyXG4gICAgaWYgKGJ1cmdlciAmJiBjb2xsYXBzZSkge1xyXG4gICAgICAgIGJ1cmdlci5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsICgpID0+IHtcclxuICAgICAgICAgICAgY29uc3QgaXNPcGVuID0gY29sbGFwc2UuY2xhc3NMaXN0LnRvZ2dsZSgnbmF2YmFyX19jb2xsYXBzZS0tb3BlbicpO1xyXG4gICAgICAgICAgICBidXJnZXIuY2xhc3NMaXN0LnRvZ2dsZSgnbmF2YmFyX19idXJnZXItLW9wZW4nLCBpc09wZW4pO1xyXG4gICAgICAgICAgICBidXJnZXIuc2V0QXR0cmlidXRlKCdhcmlhLWV4cGFuZGVkJywgaXNPcGVuKTtcclxuICAgICAgICB9KTtcclxuICAgIH1cclxuXHJcbiAgICAvLyDilIDilIAgVG9nZ2xlIHByb2dyZXNzaW9uIGZpZWxkIHZpc2liaWxpdHkgYmFzZWQgb24gZ2FtZSBzdGF0dXMg4pSA4pSAXHJcbiAgICBjb25zdCBzdGF0dXRTZWxlY3QgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndXNlcl9nYW1lX2NvbGxlY3Rpb25fc3RhdHV0Jyk7XHJcbiAgICBpZiAoIXN0YXR1dFNlbGVjdCkgcmV0dXJuO1xyXG5cclxuICAgIGNvbnN0IHByb2dyZXNzaW9uUm93ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3VzZXJfZ2FtZV9jb2xsZWN0aW9uX3Byb2dyZXNzaW9uJyk/LmNsb3Nlc3QoJy5mb3JtLWdyb3VwJylcclxuICAgICAgICB8fCBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndXNlcl9nYW1lX2NvbGxlY3Rpb25fcHJvZ3Jlc3Npb24nKT8ucGFyZW50RWxlbWVudDtcclxuICAgIGlmICghcHJvZ3Jlc3Npb25Sb3cpIHJldHVybjtcclxuXHJcbiAgICBjb25zdCB0b2dnbGVQcm9ncmVzc2lvbiA9ICgpID0+IHtcclxuICAgICAgICBjb25zdCBzaG93ID0gWydlbl9jb3VycycsICdlbl9wYXVzZSddLmluY2x1ZGVzKHN0YXR1dFNlbGVjdC52YWx1ZSk7XHJcbiAgICAgICAgcHJvZ3Jlc3Npb25Sb3cuc3R5bGUuZGlzcGxheSA9IHNob3cgPyAnJyA6ICdub25lJztcclxuICAgIH07XHJcblxyXG4gICAgdG9nZ2xlUHJvZ3Jlc3Npb24oKTtcclxuICAgIHN0YXR1dFNlbGVjdC5hZGRFdmVudExpc3RlbmVyKCdjaGFuZ2UnLCB0b2dnbGVQcm9ncmVzc2lvbik7XHJcbn0pO1xyXG4iLCIvLyBleHRyYWN0ZWQgYnkgbWluaS1jc3MtZXh0cmFjdC1wbHVnaW5cbmV4cG9ydCB7fTsiXSwibmFtZXMiOlsiZG9jdW1lbnQiLCJhZGRFdmVudExpc3RlbmVyIiwiX2RvY3VtZW50JGdldEVsZW1lbnRCIiwiX2RvY3VtZW50JGdldEVsZW1lbnRCMiIsImJ1cmdlciIsInF1ZXJ5U2VsZWN0b3IiLCJjb2xsYXBzZSIsImlzT3BlbiIsImNsYXNzTGlzdCIsInRvZ2dsZSIsInNldEF0dHJpYnV0ZSIsInN0YXR1dFNlbGVjdCIsImdldEVsZW1lbnRCeUlkIiwicHJvZ3Jlc3Npb25Sb3ciLCJjbG9zZXN0IiwicGFyZW50RWxlbWVudCIsInRvZ2dsZVByb2dyZXNzaW9uIiwic2hvdyIsImluY2x1ZGVzIiwidmFsdWUiLCJzdHlsZSIsImRpc3BsYXkiXSwic291cmNlUm9vdCI6IiJ9
(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/marked-houses"],{

/***/ "./resources/js/helpers.js":
/*!*********************************!*\
  !*** ./resources/js/helpers.js ***!
  \*********************************/
/*! exports provided: addClass, removeClass, toggleClass, getNumberOfHeader */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addClass", function() { return addClass; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "removeClass", function() { return removeClass; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "toggleClass", function() { return toggleClass; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getNumberOfHeader", function() { return getNumberOfHeader; });
function addClass(element, nameClass) {
  if (!element) return;
  var arr = element.className.split(" ");

  if (arr.indexOf(nameClass) == -1) {
    arr.push(nameClass);
  }

  element.className = arr.join(" ");
}

function removeClass(element, nameClass) {
  if (!element) return;
  var classes = element.className.split(" ");
  var i = classes.indexOf(nameClass);

  if (i >= 0) {
    classes.splice(i, 1);
    element.className = classes.join(" ");
  }
}

function toggleClass(element, nameClass) {
  if (!element) return;
  element.classList.toggle(nameClass);
}

var getNumberOfHeader = function getNumberOfHeader(header) {
  return header.split(" ")[1];
};



/***/ }),

/***/ "./resources/js/marked-houses.js":
/*!***************************************!*\
  !*** ./resources/js/marked-houses.js ***!
  \***************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _helpers_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helpers.js */ "./resources/js/helpers.js");
window.Vue = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");

new Vue({
  el: "#app",
  data: function data() {
    return {
      bulk: [],
      parsedBulk: {},
      markedHouses: {},
      selectedGroup: "",
      actualGroup: {},
      availableTerritories: {},
      selectedTerritory: "",
      actualTerritory: []
    };
  },
  mounted: function mounted() {
    this.parsedBulk = this.parseBulk();
    this.markedHouses = this.parsedBulk["markedHouses"];
    this.availableTerritories = this.parsedBulk["allTerritories"];
    this.actualGroup = this.parsedBulk["allTerritories"];
  },
  methods: {
    parseBulk: function parseBulk() {
      var parsedBulk = {};
      var markedHouses = {};
      var allTerritories = {};
      var headerNumber = 0;

      for (var i = 0; i < this.bulk.length; i++) {
        if (this.bulk[i].length == 1) {
          var headerNumber = Object(_helpers_js__WEBPACK_IMPORTED_MODULE_0__["getNumberOfHeader"])(this.bulk[i][0]);
          markedHouses[headerNumber] = {};
        } else {
          var territoryNumber = this.bulk[i][0];
          var row = {
            territory_group: headerNumber,
            territory_number: territoryNumber,
            territory_description: this.bulk[i][1],
            block: this.bulk[i][2],
            houses: this.bulk[i][3]
          };

          if (allTerritories[territoryNumber]) {
            markedHouses[headerNumber][territoryNumber].push(row);
            allTerritories[territoryNumber].push(row);
          } else {
            markedHouses[headerNumber][territoryNumber] = [row];
            allTerritories[territoryNumber] = [row];
          }
        }
      }

      return {
        markedHouses: markedHouses,
        allTerritories: allTerritories
      };
    },
    selectGroup: function selectGroup() {
      if (this.selectedGroup) {
        this.actualGroup = this.markedHouses[this.selectedGroup];
        this.availableTerritories = this.markedHouses[this.selectedGroup];
        this.selectedTerritory = "";
      } else {
        this.actualGroup = this.parsedBulk["allTerritories"];
        this.availableTerritories = this.parsedBulk["allTerritories"];
        this.selectedTerritory = "";
      }
    },
    selectTerritory: function selectTerritory() {
      if (this.selectedTerritory) {
        this.actualTerritory = this.availableTerritories[this.selectedTerritory];
      } else {
        this.actualTerritory = [];
      }
    }
  }
});

/***/ }),

/***/ 1:
/*!*********************************************!*\
  !*** multi ./resources/js/marked-houses.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/hali/Documents/Playground/Laravel/safe-ocean-45422/resources/js/marked-houses.js */"./resources/js/marked-houses.js");


/***/ })

},[[1,"/js/manifest","/js/vendor"]]]);
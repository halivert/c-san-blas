window.Vue = require("vue");
import { getNumberOfHeader } from "./helpers.js";

new Vue({
  el: "#app",
  data() {
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
  mounted() {
    this.parsedBulk = this.parseBulk();
    this.markedHouses = this.parsedBulk["markedHouses"];
    this.availableTerritories = this.parsedBulk["allTerritories"];
    this.actualGroup = this.parsedBulk["allTerritories"];
  },
  methods: {
    parseBulk() {
      var parsedBulk = {};
      var markedHouses = {};
      var allTerritories = {};
      var headerNumber = 0;
      for (var i = 0; i < this.bulk.length; i++) {
        if (this.bulk[i].length == 1) {
          var headerNumber = getNumberOfHeader(this.bulk[i][0]);
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
    selectGroup() {
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
    selectTerritory() {
      if (this.selectedTerritory) {
        this.actualTerritory = this.availableTerritories[
          this.selectedTerritory
        ];
      } else {
        this.actualTerritory = [];
      }
    }
  }
});

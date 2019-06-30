window.Vue = require("vue");
import { addClass, removeClass, toggleClass } from "./helpers.js";

Vue.component("modal", require("./components/bulma/Modal.vue").default);

Vue.component(
  "confirm-message",
  require("./components/bulma/ConfirmMessage.vue").default
);

new Vue({
  el: "#app",
  data() {
    return {
      bulk: [],
      days: [
        { id: 0, value: "Lunes" },
        { id: 1, value: "Martes" },
        { id: 2, value: "Miércoles" },
        { id: 3, value: "Jueves" },
        { id: 4, value: "Viernes" }
      ],
      day: "",
      publishersListReady: false,
      publishers: {},
      publishersAssigned: [],
      publishersFiltered: [],
      result: "",
      showedModal: false
    };
  },
  mounted() {
    if (localStorage.publishersAssigned) {
      this.publishersAssigned = JSON.parse(localStorage.publishersAssigned);
    }

    var instance = this;
    this.bulk.forEach(row => {
      if (row.length >= 6) {
        let horario = [
          !row[6] ? false : row[6].split(","),
          !row[7] ? false : row[7].split(","),
          !row[8] ? false : row[8].split(","),
          !row[9] ? false : row[9].split(","),
          !row[10] ? false : row[10].split(",")
        ];
        let publisher = {
          id: row[0],
          name: row[2],
          schedules: horario,
          phone: row[11],
          available: !row[12]
        };
        instance.publishers[publisher.id] = publisher;
      }
    });

    for (var i = 0; i < this.publishersAssigned.length; i++) {
      var publisherId = this.publishersAssigned[i].id;

      this.publishersAssigned[i].name = this.publishers[publisherId].name;
      this.publishersAssigned[i].schedules = this.publishers[publisherId].schedules;
      this.publishersAssigned[i].phone = this.publishers[publisherId].phone;
      this.publishersAssigned[i].available = this.publishers[publisherId].available;

      this.publishers[publisherId].available = false;
    }

    document.addEventListener("click", function() {
      instance.closeDropdowns();
    });
  },
  watch: {
    publishersAssigned(newPublishers) {
      localStorage.publishersAssigned = JSON.stringify(newPublishers);
    }
  },
  methods: {
    showModal() {
      this.showedModal = true;
      addClass(document.documentElement, "is-clipped");
    },
    hideModal() {
      this.showedModal = false;
      removeClass(document.documentElement, "is-clipped");
    },
    filterPublishers() {
      this.publishersFiltered = [];
      if (this.validDay(this.day)) {
        Object.keys(this.publishers).forEach(key => {
          const publisher = this.publishers[key];
          if (
            publisher.schedules[this.day] !== false &&
            publisher.available
          ) {
            this.publishersFiltered.push(publisher);
          }
        });
      } else {
        this.publishersFiltered = [];
      }
    },
    validDay(day) {
      return typeof day === "number" && 0 <= day && day <= this.days.length;
    },
    selectPublisher(publisher, schedule) {
      let selectedDay = this.days.find(day => day.id == this.day);
      if (selectedDay) publisher.assigned = selectedDay;

      if (schedule) publisher.activeSchedule = schedule;
      else publisher.activeSchedule = "";

      this.publishersAssigned.push(publisher);
      this.publishersAssigned.sort((a, b) => a.assigned.id - b.assigned.id);

      this.publishers[publisher.id].available = false;
      this.filterPublishers();
    },
    removePublisher(publisher) {
      let index = this.publishersAssigned.indexOf(publisher);
      if (index >= 0) this.publishersAssigned.splice(index, 1);

      this.publishers[publisher.id].available = true;
      this.filterPublishers();
    },
    showAssigned() {
      this.showModal();
    },
    confirmDeleteAssigned() {
      if (this.publishersAssigned.length > 0)
        this.$refs.confirmDeleteModal.showModal();
    },
    deleteAssigned() {
      this.$refs.confirmDeleteModal.hideModal();

      if (this.publishersAssigned.length > 0)
        this.publishersAssigned.forEach(publisher => {
          this.publishers[publisher.id].available = true;
        });

      this.publishersAssigned = [];
      this.filterPublishers();
    },
    preselectPublisher(evt) {
      addClass(evt.target.closest("tr"), "is-selected");
    },
    preunselectPublisher(evt) {
      removeClass(evt.target.closest("tr"), "is-selected");
    },
    showDropdown(evt, publisher) {
      this.closeDropdowns();
      const dropDown = evt.target.closest("div.dropdown");
      if (publisher) {
        if (publisher.schedules && publisher.schedules[this.day]) {
          if (publisher.schedules[this.day].length === 1) {
            return this.selectPublisher(
              publisher,
              publisher.schedules[this.day][0]
            );
          }
        }
      }

      if (dropDown) {
        toggleClass(dropDown, "is-active");
      }
    },
    closeDropdowns() {
      const dropdowns = document.querySelectorAll("div.dropdown.is-active");
      if (dropdowns) {
        dropdowns.forEach(function(item) {
          removeClass(item, "is-active");
        });
      }
    },
    callPublisher(tel) {
      if (tel) {
        window.open("tel:" + tel, "_blank");
      }
    }
  }
});

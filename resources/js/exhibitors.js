window.Vue = require("vue");
import { addClass, removeClass, toggleClass } from "./helpers";
import Modal from "./components/bulma/Modal";
import ConfirmMessage from "./components/bulma/ConfirmMessage";

new Vue({
  el: "#app",
  components: {
    Modal,
    ConfirmMessage
  },
  data() {
    return {
      bulk: [],
      selectedFilter: "1",
      days: [
        { id: 0, value: "Lunes" },
        { id: 1, value: "Martes" },
        { id: 2, value: "Miércoles" },
        { id: 3, value: "Jueves" },
        { id: 4, value: "Viernes" }
      ],
      selectedDay: "",
      publishers: {},
      publishersAssigned: [],
      publishersFiltered: [],
      modalShown: false,
      schedules: [],
      selectedSchedule: ""
    };
  },
  mounted() {
    if (localStorage.publishersAssigned) {
      this.publishersAssigned = JSON.parse(localStorage.publishersAssigned);
    }

    var instance = this;

    this.parsePublishers();

    for (var i = 0; i < this.publishersAssigned.length; i++) {
      var publisherId = this.publishersAssigned[i].id;

      this.publishersAssigned[i].name = this.publishers[publisherId].name;
      this.publishersAssigned[i].schedules = this.publishers[
        publisherId
      ].schedules;
      this.publishersAssigned[i].phone = this.publishers[publisherId].phone;
      this.publishersAssigned[i].available = this.publishers[
        publisherId
      ].available;

      this.publishers[publisherId].available = false;
    }

    document.addEventListener("click", function() {
      instance.closeDropdowns();
    });
  },
  watch: {
    publishersAssigned(newPublishers) {
      localStorage.publishersAssigned = JSON.stringify(newPublishers);
    },
    selectedFilter() {
      this.publishersFiltered = [];
      this.selectedDay = "";
      this.selectedSchedule = "";
    },
    selectedDay() {
      this.filterByDay();
    },
    selectedSchedule() {
      this.filterBySchedule();
    }
  },
  methods: {
    showModal() {
      this.modalShown = true;
      addClass(document.documentElement, "is-clipped");
    },
    hideModal() {
      this.modalShown = false;
      removeClass(document.documentElement, "is-clipped");
    },
    filterByDay() {
      this.publishersFiltered = [];
      if (this.validDay(this.selectedDay)) {
        Object.keys(this.publishers).forEach(key => {
          let publisher = this.publishers[key];
          if (
            publisher.schedules[this.selectedDay] !== false &&
            publisher.available
          ) {
            this.publishersFiltered.push(publisher);
          }
        });
      } else {
        this.publishersFiltered = [];
      }
    },
    filterBySchedule() {
      this.publishersFiltered = [];
      let schedule = this.schedules.find(s => s.value === this.selectedSchedule);
      if (schedule) {
        Object.keys(this.publishers).forEach(key => {
          let publisher = this.publishers[key];
          if (publisher.days[this.selectedSchedule] && publisher.available) {
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
    selectPublisherDay(publisher, schedule) {
      let selectedDay = this.days.find(d => d.id == this.selectedDay);
      if (selectedDay) publisher.assigned = selectedDay;

      if (schedule) publisher.activeSchedule = schedule;
      else publisher.activeSchedule = "";

      this.publishersAssigned.push(publisher);
      this.publishersAssigned.sort((a, b) => a.assigned.id - b.assigned.id);

      this.publishers[publisher.id].available = false;
      this.filterByDay();
    },
    selectPublisherSchedule(publisher, day) {
      let selectedDay = this.days.find(d => d.value == day);
      if (selectedDay) publisher.assigned = selectedDay;

      if (this.selectedSchedule) publisher.activeSchedule = this.selectedSchedule;
      else publisher.activeSchedule = "";

      this.publishersAssigned.push(publisher);
      this.publishersAssigned.sort((a, b) => a.assigned.id - b.assigned.id);

      this.publishers[publisher.id].available = false;
      this.filterBySchedule();
    },
    removePublisher(publisher) {
      let index = this.publishersAssigned.indexOf(publisher);
      if (index >= 0) this.publishersAssigned.splice(index, 1);

      this.publishers[publisher.id].available = true;
			if (this.selectedDay) this.filterByDay();
			if (this.selectedSchedule) this.filterBySchedule();
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
      this.filterByDay();
    },
    preselectPublisher(evt) {
      addClass(evt.target.closest("tr"), "is-selected");
    },
    preunselectPublisher(evt) {
      removeClass(evt.target.closest("tr"), "is-selected");
    },
    showDropdownDay(evt, publisher) {
      this.closeDropdowns();
      let dropDown = evt.target.closest("div.dropdown");
      if (publisher) {
        if (publisher.schedules && publisher.schedules[this.selectedDay]) {
          if (publisher.schedules[this.selectedDay].length === 1) {
            return this.selectPublisherDay(
              publisher,
              publisher.schedules[this.selectedDay][0]
            );
          }
        }
      }

      if (dropDown) {
        toggleClass(dropDown, "is-active");
      }
    },
    showDropdownSchedule(evt, publisher) {
      this.closeDropdowns();
      let dropDown = evt.target.closest("div.dropdown");
      if (publisher) {
        if (publisher.schedules && publisher.days[this.selectedSchedule]) {
          if (publisher.days[this.selectedSchedule].length === 1) {
            return this.selectPublisherSchedule(
              publisher,
              publisher.days[this.selectedSchedule][0]
            );
          }
        }
      }

      if (dropDown) {
        toggleClass(dropDown, "is-active");
      }
    },
    closeDropdowns() {
      let dropdowns = document.querySelectorAll("div.dropdown.is-active");
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
    },
    parsePublishers() {
      this.schedules = [];

      let instance = this;
			let schedulesSet = {};

      this.bulk.forEach(row => {
        if (row.length >= 6) {
          let schedules = [
            !row[6] ? false : row[6].split(","),
            !row[7] ? false : row[7].split(","),
            !row[8] ? false : row[8].split(","),
            !row[9] ? false : row[9].split(","),
            !row[10] ? false : row[10].split(",")
          ];

          let publisher = {
            id: row[0],
            name: row[2],
            schedules: schedules,
            phone: row[11],
            available: !row[12],
            days: {}
          };

          for (var i = 0; i < schedules.length; i++) {
            if (schedules[i]) {
              schedules[i].forEach(schedule => {
                schedule = schedule.replace(/\s+/g, "").replace(/-/g, " - ");
                if (!schedulesSet[schedule]) {
                  schedulesSet[schedule] = {};
                  instance.schedules.push({
                    id: 0,
                    value: schedule
                  });
                }

                if (!publisher.days[schedule]) publisher.days[schedule] = [];

                publisher.days[schedule].push(instance.days[i].value);
              });
            }
          }

          instance.publishers[publisher.id] = publisher;
        }
      });

      for (var i = 0; i < this.schedules.length; i++) {
        this.schedules[i].id = i;
      }
    }
  }
});

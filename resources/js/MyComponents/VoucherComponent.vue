<template>
  <div class="max-w-7xl mx-auto pt-3 px-4 sm:px-6 lg:px-8">
    <!-- Title -->
    <h1
      class="text-lg font-semibold tracking-widest text-gray-900 uppercase dark-mode:text-white"
    >
      Vouchers
    </h1>
    <!-- End Title -->
  </div>
  <jet-bar-container>
    <jet-bar-alert
      v-on:click="removeAlertData()"
      v-show="alert_status"
      :text="alertmessage"
      :type="type"
    />
    <div class="flex items-center justify-center">
      <j-button
        v-on:click="generateVoucher()"
        class="justify-items-center bg-green-800 hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-30 py-5 mx-auto my-5"
      >
        Generate Voucher</j-button
      >
    </div>

    <jet-bar-table :headers="['Voucher Codes']">
      <tr
        class="hover:bg-gray-50"
        v-for="voucher_code in voucher_codes.data"
        :key="voucher_code.id"
      >
        <jet-bar-table-data>{{ voucher_code.voucher_code }}</jet-bar-table-data>
      </tr>
    </jet-bar-table>
    <jet-bar-paginate :items="voucher_codes" @nextLink="paginate1($event)" />
  </jet-bar-container>
</template>

<script>
import JetBarContainer from "@/Components/JetBarContainer";
import JetBarAlert from "@/Components/JetBarAlert";
import JetBarStatsContainer from "@/Components/JetBarStatsContainer";
import JetBarStatCard from "@/Components/JetBarStatCard";
import JetBarTable from "@/Components/JetBarTable";
import JetBarTableData from "@/Components/JetBarTableData";
import JetBarBadge from "@/Components/JetBarBadge";
import JetBarIcon from "@/Components/JetBarIcon";
import JetBarPaginate from "@/Components/JetBarSimplePagination";
import JButton from "@/Jetstream/Button";
import axios from "axios";

export default {
  components: {
    JetBarContainer,
    JetBarAlert,
    JetBarStatsContainer,
    JetBarStatCard,
    JetBarTable,
    JetBarTableData,
    JetBarBadge,
    JetBarIcon,
    JButton,
    JetBarPaginate,
  },
  data() {
    return {
      voucher_codes: [],
      alert_status: 0,
      alertmessage: "",
      type: "info",
    };
  },
  mounted() {
    this.getVoucher();
  },
  methods: {
    generateVoucher() {
      axios
        .post(route("create.voucher"))
        .then((res) => {
          this.getVoucher();
          this.alert_status = 1;
          this.alertmessage = "Generated Successfull";
          this.type = "success";
        })
        .catch((e) => console.log(e));
    },
    getVoucher() {
      axios
        .get(route("get.voucher"))
        .then((res) => {
          this.voucher_codes = res.data;
          console.log(this.voucher_codes);
        })
        .catch((e) => console.log(e));
    },
    removeAlertData() {
      this.alert_status = 0;
      this.alertmessage = "";
      this.type = "info";
    },
    paginate1(href) {
      axios
        .get(href)
        .then((res) => {
          this.voucher_codes = res.data;
          console.log(this.voucher_codes);
        })
        .catch((e) => console.log(e));
    },
  },
};
</script>

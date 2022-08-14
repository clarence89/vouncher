<template>
  <div class="max-w-7xl mx-auto pt-3 px-4 sm:px-6 lg:px-8">
    <!-- Title -->
    <h1
      class="text-lg font-semibold tracking-widest text-gray-900 uppercase dark-mode:text-white"
    >
      Dashboard
    </h1>
    <!-- End Title -->
  </div>
  <jet-bar-container>
    <jet-bar-alert text="This is an alert message." type="danger" />

    <jet-bar-stats-container>
      <jet-bar-stat-card title="Total clients" number="6389" type="warning">
        <template v-slot:icon>
          <jet-bar-icon type="users" fill />
        </template>
      </jet-bar-stat-card>

      <jet-bar-stat-card title="Account balance" number="$ 46,760.89" type="success">
        <template v-slot:icon>
          <jet-bar-icon type="money" fill />
        </template>
      </jet-bar-stat-card>

      <jet-bar-stat-card title="New sales" number="376" type="info">
        <template v-slot:icon>
          <jet-bar-icon type="cart" fill />
        </template>
      </jet-bar-stat-card>

      <jet-bar-stat-card title="Pending contacts" number="35" type="danger">
        <template v-slot:icon>
          <jet-bar-icon type="message" fill />
        </template>
      </jet-bar-stat-card>
    </jet-bar-stats-container>

    <jet-bar-table :headers="['name', 'title', 'status', 'role', '', '']">
      <tr class="hover:bg-gray-50" v-for="index in 3" :key="index">
        <jet-bar-table-data>Jane Cooper</jet-bar-table-data>
        <jet-bar-table-data>
          <div class="text-sm text-gray-900">Regional Paradigm Technician</div>
          <div class="text-sm text-gray-500">Optimization</div>
        </jet-bar-table-data>
        <jet-bar-table-data>
          <jet-bar-badge text="Active" type="success" />
        </jet-bar-table-data>
        <jet-bar-table-data>Admin</jet-bar-table-data>
        <jet-bar-table-data>
          <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
        </jet-bar-table-data>
        <jet-bar-table-data>
          <a href="#" class="text-gray-400 hover:text-gray-500">
            <jet-bar-icon type="trash" fill />
          </a>
        </jet-bar-table-data>
      </tr>
    </jet-bar-table>
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
  },
  props: ["profiled"],
};
</script>

<template>
  <div class="font-sans antialiased bg-gray-100">
    <jet-banner />

    <div class="lg:flex flex-col lg:flex-row lg:min-h-screen w-full">
      <!-- Sidebar -->
      <jet-bar-sidebar @displayData="fetchData($event)" />
      <!-- End Sidebar -->

      <div class="w-full">
        <!-- Top Navbar -->
        <jet-bar-navigation-menu />
        <!-- End Top Navbar -->

        <!-- Content Container -->
        <main
          class="flex-1 relative z-0 overflow-y-auto py-6 focus:outline-none"
          tabindex="0"
        >
          <!-- Replace with your content -->
          <div>
            <!-- Content -->

            <div class="min-h-full lg:min-h-96 px-4 sm:px-0">
              <slot v-bind:profiled="profiled"></slot>
            </div>
            <!-- End Content -->
          </div>
          <!-- /End replace -->
        </main>
        <!-- Content Container -->
      </div>
    </div>
  </div>
</template>

<script>
import JetApplicationMark from "@/Jetstream/ApplicationMark";
import JetBanner from "@/Jetstream/Banner";
import JetDropdown from "@/Jetstream/Dropdown";
import JetDropdownLink from "@/Jetstream/DropdownLink";
import JetNavLink from "@/Jetstream/NavLink";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink";
import JetBarNavigationMenu from "@/Components/JetBarNavigationMenu";
import JetBarSidebar from "@/Components/JetBarSidebar";

export default {
  components: {
    JetBarSidebar,
    JetBarNavigationMenu,
    JetApplicationMark,
    JetBanner,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetResponsiveNavLink,
  },
  data() {
    return {
      profiled: 1,
    };
  },
  methods: {
    fetchData(data) {
      this.profiled = data;
    },
  },
};
</script>

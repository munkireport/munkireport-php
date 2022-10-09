import Dashboard from './views/Dashboard.vue';
import BusinessUnits from './business-units/BusinessUnits.vue';

export default [
  { path: '/dashboards/:slug', component: Dashboard, },
  { path: '/business_units', component: BusinessUnits, },
  { path: '/business_units/:id', component: BusinessUnits, }
]

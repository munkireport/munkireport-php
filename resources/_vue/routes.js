import Dashboard from './views/Dashboard.vue';
import BusinessUnits from './business-units/BusinessUnits.vue';


export default [
  { path: '/dashboards', component: Dashboard, },
  { path: '/dashboards/:slug', component: Dashboard, },
  { path: '/business-units', component: BusinessUnits, },
  { path: '/business-units/:id', component: BusinessUnits, },
]

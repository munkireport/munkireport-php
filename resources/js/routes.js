import Dashboard from './views/Dashboard';
import BusinessUnits from './business-units/BusinessUnits';

export default [
  { path: '/dashboards/:slug', component: Dashboard, },
  { path: '/business_units', component: BusinessUnits, },
  { path: '/business_units/:id', component: BusinessUnits, }
]

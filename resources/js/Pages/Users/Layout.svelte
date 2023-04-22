<script>
  import { inertia, page } from '@inertiajs/svelte'
  import {
    Collapse,
    Dropdown,
    DropdownToggle,
    DropdownMenu,
    Icon,
    Nav,
    Navbar,
    NavbarBrand,
    NavbarToggler,
    NavItem,
    NavLink, DropdownItem
  } from 'sveltestrap';
  import { i18n } from '@/i18n.js';

  let isOpen = false;

  function handleUpdate(event) {
    isOpen = event.detail.isOpen;
  }

</script>

<main>
    <Navbar color="light" light expand="lg" fixed="top">
        <NavbarBrand href="/">{$page.props.appName}</NavbarBrand>
        <NavbarToggler on:click={() => (isOpen = !isOpen)} />
        <Collapse {isOpen} navbar expand="md" on:update={handleUpdate}>
            <Dropdown nav inNavbar>
                {#if $page.props.dashboards.length === 1}
                    <DropdownItem>{$i18n.t('nav.main.dashboard')}</DropdownItem>
                {:else}
                    <DropdownToggle nav caret>TRANSLATE_DASHBOARD</DropdownToggle>
                    <DropdownMenu right>
                        {#each $page.props.dashboards as dashboard}
                            <DropdownItem>{dashboard.display_name}</DropdownItem>
                        {/each}
                    </DropdownMenu>
                {/if}
            </Dropdown>

            <Dropdown nav inNavbar>
                <DropdownToggle nav caret><Icon name="bar-chart" />TRANSLATE_REPORTS</DropdownToggle>
                <DropdownMenu right>
                    {#each $page.props.reports as report}
                        <DropdownItem class="{report.class}" href="{report.url}">{report.i18n}</DropdownItem>
                    {/each}
                </DropdownMenu>
            </Dropdown>
        </Collapse>
    </Navbar>
</main>

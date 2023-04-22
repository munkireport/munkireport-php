<script lang="ts">
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
  import InertiaDropdownItem from '../Components/InertiaDropdownItem.svelte';
  import { i18n } from '../i18n';
  import { isLoading } from "svelte-i18next";

  let isOpen = false;

  function handleUpdate(event) {
    isOpen = event.detail.isOpen;
  }

</script>

<main>
    <Navbar color="light" light expand="lg" fixed="top">
        <NavbarBrand href="/">{$page.props.appName}</NavbarBrand>
        <NavbarToggler on:click={() => (isOpen = !isOpen)} />
        {#if $isLoading}{:else}
        <Collapse {isOpen} navbar expand="md" on:update={handleUpdate}>
            <Nav navbar>
                {#if $page.props.dashboards.length === 1}
                    <NavItem>
                        <NavLink>{$i18n.t('nav.main.dashboard')}</NavLink>
                    </NavItem>
                {:else}
                    <Dropdown nav inNavbar>
                            <DropdownToggle nav caret>{$i18n.t('nav.main.dashboard_plural')}</DropdownToggle>
                            <DropdownMenu right>
                                {#each $page.props.dashboards as dashboard}
                                    <DropdownItem>{dashboard.display_name}</DropdownItem>
                                {/each}
                            </DropdownMenu>
                    </Dropdown>
                {/if}

                <Dropdown nav inNavbar>
                    <DropdownToggle nav caret>
                        <Icon name="bar-chart" /> {$i18n.t('nav.main.reports')}
                    </DropdownToggle>
                    <DropdownMenu>
                        {#each $page.props.reports as report}
                            <InertiaDropdownItem class="{report.class}" href="{report.url}">{$i18n.t(report.i18n)}</InertiaDropdownItem>
                        {/each}
                    </DropdownMenu>
                </Dropdown>

                <Dropdown nav inNavbar>
                    <DropdownToggle nav caret>
                        <Icon name="card-list" /> {$i18n.t('nav.main.listings')}
                    </DropdownToggle>
                    <DropdownMenu>
                        {#each $page.props.listings as listing}
                            <InertiaDropdownItem class="{listing.class}" href="{listing.url}">{$i18n.t(listing.i18n)}</InertiaDropdownItem>
                        {/each}
                    </DropdownMenu>
                </Dropdown>

                <Dropdown nav inNavbar>
                    <DropdownToggle nav caret>
                        <Icon name="card-list" /> {$i18n.t('nav.main.admin')}
                    </DropdownToggle>
                    <DropdownMenu>
                        {#each $page.props.admin as item}
                            <InertiaDropdownItem class="{item.class}" href="{item.url}">{$i18n.t(item.i18n)}</InertiaDropdownItem>
                        {/each}
                    </DropdownMenu>
                </Dropdown>
            </Nav>

            <Nav navbar>
                <Dropdown nav inNavbar>
                    <DropdownToggle nav caret>
                        <Icon name="person" /> {$page.props.user.email}
                    </DropdownToggle>
                    <DropdownMenu>
                        <DropdownItem>{$i18n.t('nav.user.tokens')}</DropdownItem>
                        <DropdownItem divider />
                        <DropdownItem>{$i18n.t('nav.user.logout')}</DropdownItem>
                    </DropdownMenu>
                </Dropdown>
            </Nav>
        </Collapse>
            {/if}
    </Navbar>
</main>

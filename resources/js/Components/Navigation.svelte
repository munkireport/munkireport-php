<script lang="ts">
  import { page } from '@inertiajs/svelte'
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
      NavLink, DropdownItem, Form, InputGroup, Input, InputGroupAddon, Button
  } from 'sveltestrap';
  import { FontAwesomeIcon } from "@fortawesome/svelte-fontawesome";
  import {
      faPowerOff,
      faThLarge,
      faSearch,
      faUser,
  } from '@fortawesome/free-solid-svg-icons'
  import {
      faChartBar,
      faListAlt,
  } from '@fortawesome/free-regular-svg-icons'

  import { i18n } from '../i18n';
  import { isLoading } from "svelte-i18next";

  import InertiaNavLink from "./InertiaNavLink.svelte";
  import InertiaDropdownItem from '../Components/InertiaDropdownItem.svelte';

  let isOpen = false;

  function handleUpdate(event) {
    isOpen = event.detail.isOpen;
  }

</script>


<Navbar color="light" light expand="lg" fixed="top">
    <NavbarBrand href="/">{$page.props.appName}</NavbarBrand>
    <NavbarToggler on:click={() => (isOpen = !isOpen)} />
    {#if $isLoading}{:else}
    <Collapse {isOpen} navbar expand="md" on:update={handleUpdate}>
        <Nav navbar>
            {#if $page.props.dashboards.length === 1}
                <NavItem>
                    <InertiaNavLink href="/">
                        <FontAwesomeIcon icon={faThLarge} />
                        {$i18n.t('nav.main.dashboard')}
                    </InertiaNavLink>
                </NavItem>
            {:else}
                <Dropdown nav inNavbar>
                        <DropdownToggle nav caret>
                            <FontAwesomeIcon icon={faThLarge} />
                            {$i18n.t('nav.main.dashboard_plural')}
                        </DropdownToggle>
                        <DropdownMenu>
                            {#each $page.props.dashboards as dashboard}
                                <DropdownItem>{dashboard.display_name}</DropdownItem>
                            {/each}
                        </DropdownMenu>
                </Dropdown>
            {/if}

            <Dropdown nav inNavbar>
                <DropdownToggle nav caret>
                    <FontAwesomeIcon icon={faChartBar} /> {$i18n.t('nav.main.reports')}
                </DropdownToggle>
                <DropdownMenu>
                    {#each $page.props.reports as report}
                        <DropdownItem class="{report.class}" href="{report.url}">{$i18n.t(report.i18n)}</DropdownItem>
                    {/each}
                </DropdownMenu>
            </Dropdown>

            <Dropdown nav inNavbar>
                <DropdownToggle nav caret>
                    <FontAwesomeIcon icon={faListAlt} /> {$i18n.t('nav.main.listings')}
                </DropdownToggle>
                <DropdownMenu>
                    {#each $page.props.listings as listing}
                        <DropdownItem class="{listing.class}" href="{listing.url}">{$i18n.t(listing.i18n)}</DropdownItem>
                    {/each}
                </DropdownMenu>
            </Dropdown>

            <Dropdown nav inNavbar>
                <DropdownToggle nav caret>
                    <FontAwesomeIcon icon={faListAlt} /> {$i18n.t('nav.main.admin')}
                </DropdownToggle>
                <DropdownMenu>
                    {#each $page.props.admin as item}
                        <DropdownItem class="{item.class}" href="{item.url}">{$i18n.t(item.i18n)}</DropdownItem>
                    {/each}
                </DropdownMenu>
            </Dropdown>
        </Nav>

        <Nav navbar class="ml-auto">
            <Form inline>
                <InputGroup>
                    <Input type="search" placeholder="Search" aria-label="Search" aria-haspopup="true" aria-expanded="false" />
                    <InputGroupAddon addonType="append">
                        <Button color="primary">
                            <FontAwesomeIcon icon={faSearch} />
                        </Button>
                    </InputGroupAddon>
                </InputGroup>
            </Form>

            <Dropdown nav inNavbar>
                <DropdownToggle nav caret>
                    <FontAwesomeIcon icon={faUser} />
                    {$page.props.user.email}
                </DropdownToggle>
                <DropdownMenu>
                    <InertiaDropdownItem href="/user/profile">{$i18n.t('nav.user.profile')}</InertiaDropdownItem>
                    <DropdownItem href="/me/tokens">{$i18n.t('nav.user.tokens')}</DropdownItem>
                    <DropdownItem href="/api/documentation">{$i18n.t('nav.api.documentation')}</DropdownItem>
                    <DropdownItem divider />
                    <form method="POST" action="/logout">
                        <DropdownItem tag="button" type="submit">
                            <FontAwesomeIcon icon={faPowerOff} />
                            {$i18n.t('nav.user.logout')}
                        </DropdownItem>
                    </form>
                </DropdownMenu>
            </Dropdown>
        </Nav>
    </Collapse>
        {/if}
</Navbar>


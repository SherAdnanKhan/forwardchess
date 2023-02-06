<template>
    <aside id="sidebar-left" class="sidebar-left">
        <div class="sidebar-header">
            <div class="sidebar-title">
                Navigation
            </div>
        </div>

        <div class="nano">
            <div class="nano-content">
                <nav id="menu" class="nav-main" role="navigation">
                    <ul class="nav nav-main">
                        <li v-for="item in menu" :class="{
                            'nav-parent': item.hasSubmenu,
                            'nav-active': item.active,
                            'nav-expanded': item.expanded}">

                            <menu-link :item="item" @clicked="itemClicked"></menu-link>

                            <ul v-if="item.subMenu" class="nav nav-children">
                                <li v-for="subItem in item.subMenu" :class="{'nav-active': subItem.active}">
                                    <menu-link :item="subItem" @clicked="itemClicked"></menu-link>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </aside>
</template>

<script>
    import _ from 'lodash';
    import menuItems from '../../dictionary/menu-items';
    import MenuLink from './MenuLink.vue';

    export default {
        name: 'Sidebar',

        components: {
            MenuLink
        },

        data() {
            return {
                initialized: false,
                menu       : []
            };
        },

        watch: {
            '$route.path': {
                immediate: true,
                handler  : function () {
                    this.setMenu();
                }
            }
        },

        methods: {
            setMenu() {
                const menu = [],
                      path = this.$router.currentRoute.path;

                _.each(_.extend({}, menuItems), item => {
                    let active     = (item.url === path);
                    let expanded   = false;
                    let hasSubmenu = false;

                    if (item.subMenu) {
                        _.each(item.subMenu, subItem => {
                            subItem.active = (subItem.url === path);
                            if (subItem.active) {
                                active = true;
                            }
                        });

                        expanded   = active;
                        hasSubmenu = true;
                    }

                    item.active     = active;
                    item.expanded   = expanded;
                    item.hasSubmenu = hasSubmenu;

                    menu.push(item);
                });

                this.menu = menu;
            },

            itemClicked(item) {
                if (!item.hasSubmenu) {
                    this.$router.push(item.url);
                } else {
                    const parentItem = _.find(this.menu, menuItem => (item === menuItem));
                    if (parentItem) {
                        parentItem.expanded = !parentItem.expanded;
                    }
                }
            }

        }
    };
</script>

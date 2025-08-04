<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, MapPin, Truck, Route, AlertTriangle, BarChart3, Settings, Users } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Panel Principal',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Mapa en Vivo',
        href: '/mapa-vivo',
        icon: MapPin,
    },
    {
        title: 'Gestión de Vehículos',
        href: '/vehicles',
        icon: Truck,
    },
    {
        title: 'Gestión de Usuarios',
        href: '/drivers',
        icon: Users,
    },
    {
        title: 'Rutas Optimizadas',
        href: '/routes',
        icon: Route,
    },
    {
        title: 'Alertas y Monitoreo',
        href: '/alerts',
        icon: AlertTriangle,
    },
    {
        title: 'Reportes y Análisis',
        href: '/reportes',
        icon: BarChart3,
    },
    {
        title: 'Configuración',
        href: '/configuracion',
        icon: Settings,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Documentación',
        href: '/documentacion',
        icon: BookOpen,
    },
    {
        title: 'Soporte Técnico',
        href: '/soporte',
        icon: Folder,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

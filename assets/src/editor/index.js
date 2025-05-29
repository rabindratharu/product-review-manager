/**
 * File editor/index.js.
 */
import { registerPlugin } from '@wordpress/plugins';
import PrmSidebar from "./sidebar.js";

registerPlugin('prm-sidebar', {
    render: PrmSidebar,
    icon: 'admin-plugins',
});

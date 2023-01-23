export default (options = {}) => ({
	options,
	showMenu: false,
	openSubMenus: [],

	// Opens and closes the menu.
	toggleMenu() {
		this.showMenu = !this.showMenu;
		this.$dispatch('showmenu' + this.showMenu.toString());
		document.body.classList.toggle('has-open-menu');
	},

	// Opens a sub-menu.
	openSubMenu(menuItemID, parentID) {
		if (this.isSubMenuOpen(menuItemID)) {
			return;
		}

		this.openSubMenus = this.openSubMenus.concat({
			id: menuItemID,
			parent: parentID,
		});
	},

	// Closes a sub-menu.
	closeSubMenu(menuItemID, parentID) {
		if (!this.isSubMenuOpen(menuItemID)) {
			return;
		}

		// Close the current menu item and it's children
		this.openSubMenus = this.openSubMenus.filter((openMenuItem) => {
			return (
				openMenuItem.parent !== menuItemID &&
				openMenuItem.id !== menuItemID
			);
		});
	},

	// Toggles a sub-menu.
	toggleSubMenu(menuItemID, parentID) {
		if (!this.isSubMenuOpen(menuItemID)) {
			this.openSubMenu(menuItemID, parentID);
		} else {
			this.closeSubMenu(menuItemID, parentID);
		}
	},

	// Close all sub-menus.
	closeAllSubMenus() {
		this.openSubMenus = [];
	},

	// On pointer enter (if enabled).
	onPointerEnter(menuItemID, parentID) {
		if (this.options.hover && !this.isTouchEnabled()) {
			this.openSubMenu(menuItemID, parentID);
		}
	},

	// On pointer leave (if enabled).
	onPointerLeave(menuItemID, parentID) {
		// Determines if the mouse is within the submenu or not.
		const mouseWithin = document
			.querySelector(`#menu-item-${menuItemID}`)
			.matches(':hover');

		if (this.options.hover && !this.isTouchEnabled() && !mouseWithin) {
			this.closeSubMenu(menuItemID, parentID);
		}
	},

	// Close menu on click outside.
	onClickAway(e) {
		if (!e.target.hasAttribute('data-dropdown')) {
			this.closeAllSubMenus();
		}
	},

	// Close on escape.
	onEscape(e) {
		this.closeAllSubMenus();

		const topLevelButton = e.currentTarget.querySelector('button');
		if (topLevelButton) {
			topLevelButton.focus();
		}
	},

	// Check if a sub-menu is open.
	isSubMenuOpen(menuID) {
		return this.openSubMenus.some(
			(openMenuItem) => openMenuItem.id === menuID
		);
	},

	// Check if the device is touch enabled.
	isTouchEnabled() {
		return (
			'ontouchstart' in window ||
			window.navigator.maxTouchPoints > 0 ||
			window.navigator.msMaxTouchPoints > 0
		);
	},
});

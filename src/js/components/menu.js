export default () => ({
	showMenu: false,
	openMenus: [],

	init() {
		this.setShowMenu();
	},

	setShowMenu() {
		this.showMenu = window.innerWidth > 1023;
	},

	toggleMenu() {
		this.showMenu = !this.showMenu;
		this.$dispatch('showmenu' + this.showMenu.toString());
	},

	toggleMenuList(menuItemID, parentID) {
		if (!this.isMenuOpen(menuItemID)) {
			if (parentID === 0) {
				this.closeAllMenus();
			}

			this.openMenus = this.openMenus.concat({
				id: menuItemID,
				parent: parentID,
			});

			return;
		}

		// Close the current menu item and it's children
		this.openMenus = this.openMenus.filter((openMenuItem) => {
			return (
				openMenuItem.parent !== menuItemID &&
				openMenuItem.id !== menuItemID
			);
		});
	},

	closeAllMenus() {
		this.openMenus = [];
	},

	isMenuOpen(menuID) {
		return this.openMenus.some(
			(openMenuItem) => openMenuItem.id === menuID
		);
	},

	onEscape(e) {
		this.closeAllMenus();

		const topLevelButton = e.currentTarget.querySelector('button');
		if (topLevelButton) {
			topLevelButton.focus();
		}
	},

	onClickAway(e) {
		if (!e.target.hasAttribute('data-dropdown')) {
			this.closeAllMenus();
		}
	},
});

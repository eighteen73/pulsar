import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
	Alpine.data('menu', () => ({
		showMenu: false,
		openMenus: [],

		toggleMenu(menuItemID, parentID) {
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
			if (!e.target.classList.contains('dropdown')) {
				this.closeAllMenus();
			}
		},
	}));
});

import Alpine from 'alpinejs';

// Import any custom Alpine components.
import menu from './components/menu';

// Register any custom Alpine components.
Alpine.data('menu', menu);

// Boot Alpine.
Alpine.start();

// Theme Switcher
class ThemeSwitcher {
    constructor() {
        this.currentTheme = localStorage.getItem('theme') || 'dark';
        this.init();
    }

    init() {
        // Aplicar tema guardado
        this.applyTheme(this.currentTheme);
        
        // Crear botón toggle si no existe
        this.createToggleButton();
        
        // Escuchar cambios de tema
        this.listenForThemeChanges();
    }

    createToggleButton() {
        // Buscar si ya existe el botón
        if (document.querySelector('.theme-toggle')) {
            return;
        }

        // Crear botón toggle
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'theme-toggle';
        toggleBtn.setAttribute('aria-label', 'Cambiar tema');
        toggleBtn.innerHTML = `
            <i class="fa fa-sun-o sun-icon" aria-hidden="true"></i>
            <i class="fa fa-moon-o moon-icon" aria-hidden="true"></i>
        `;

        // Buscar el navbar para insertar el botón
        const navbar = document.querySelector('.navbar-nav');
        if (navbar) {
            // Insertar antes del LogOut
            const logoutLink = navbar.querySelector('a[href*="logout"]');
            if (logoutLink) {
                logoutLink.parentNode.insertBefore(toggleBtn, logoutLink);
            } else {
                navbar.appendChild(toggleBtn);
            }
        }

        // Event listener para el botón
        toggleBtn.addEventListener('click', () => {
            this.toggleTheme();
        });
    }

    toggleTheme() {
        const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.applyTheme(newTheme);
        this.saveTheme(newTheme);
    }

    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        this.currentTheme = theme;
        
        // Actualizar iconos del botón
        this.updateToggleButton();
        
        // Emitir evento personalizado para otros scripts
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
    }

    updateToggleButton() {
        const toggleBtn = document.querySelector('.theme-toggle');
        if (toggleBtn) {
            const sunIcon = toggleBtn.querySelector('.sun-icon');
            const moonIcon = toggleBtn.querySelector('.moon-icon');
            
            if (this.currentTheme === 'light') {
                sunIcon.style.display = 'inline';
                moonIcon.style.display = 'none';
            } else {
                sunIcon.style.display = 'none';
                moonIcon.style.display = 'inline';
            }
        }
    }

    saveTheme(theme) {
        localStorage.setItem('theme', theme);
    }

    listenForThemeChanges() {
        // Escuchar cambios de tema desde otros scripts
        window.addEventListener('themeChanged', (event) => {
            this.currentTheme = event.detail.theme;
            this.updateToggleButton();
        });
    }

    // Método público para obtener el tema actual
    getCurrentTheme() {
        return this.currentTheme;
    }

    // Método público para cambiar tema programáticamente
    setTheme(theme) {
        if (['light', 'dark'].includes(theme)) {
            this.applyTheme(theme);
            this.saveTheme(theme);
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.themeSwitcher = new ThemeSwitcher();
});

// También inicializar si se carga después del DOM
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.themeSwitcher = new ThemeSwitcher();
    });
} else {
    window.themeSwitcher = new ThemeSwitcher();
}



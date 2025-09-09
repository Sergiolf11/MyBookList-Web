// Theme Switcher
class ThemeSwitcher {
    constructor() {
        this.currentTheme = localStorage.getItem('theme') || 'dark';
        this.init();
    }

    init() {
        // Aplicar tema guardado
        this.applyTheme(this.currentTheme);
        
        // Configurar botón toggle existente
        this.setupToggleButton();
        
        // Escuchar cambios de tema
        this.listenForThemeChanges();
    }

    setupToggleButton() {
        // Buscar el botón toggle existente
        const toggleBtn = document.querySelector('.theme-toggle');
        if (toggleBtn) {
            // Event listener para el botón
            toggleBtn.addEventListener('click', () => {
                this.toggleTheme();
            });
        }
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



// Sidebar functionality
class SidebarManager {
    constructor() {
        this.init();
    }

    init() {
        // Esperar a que el DOM esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.setupSidebar();
                this.observeDOMChanges();
                this.loadGenres();
            });
        } else {
            this.setupSidebar();
            this.observeDOMChanges();
            this.loadGenres();
            this.updateThemeToggle();
        }
    }

    observeDOMChanges() {
        // Crear un observer para detectar cambios en el DOM
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    // Verificar si se agregó contenido al navbar
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            // Si se cargó el header dinámicamente, configurar el sidebar
                            if (node.querySelector && node.querySelector('.sidebar-toggle')) {
                                setTimeout(() => {
                                    this.setupSidebar();
                                    this.updateThemeToggle();
                                }, 100);
                            }
                        }
                    });
                }
            });
        });

        // Observar cambios en el body
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    setupSidebar() {
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarClose = document.querySelector('.sidebar-close');

        if (!sidebarToggle || !sidebar || !sidebarOverlay) {
            // Si no existen los elementos, intentar de nuevo en 100ms
            setTimeout(() => {
                this.setupSidebar();
            }, 100);
            return;
        }

        // Verificar si ya se configuró para evitar duplicar event listeners
        if (sidebarToggle.dataset.configured) {
            return;
        }

        // Marcar como configurado
        sidebarToggle.dataset.configured = 'true';

        // Abrir sidebar
        sidebarToggle.addEventListener('click', () => {
            this.openSidebar();
        });

        // Cerrar sidebar
        if (sidebarClose) {
            sidebarClose.addEventListener('click', () => {
                this.closeSidebar();
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                this.closeSidebar();
            });
        }

        // Cerrar con tecla Escape (solo una vez)
        if (!this.escapeListenerAdded) {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                    this.closeSidebar();
                }
            });
            this.escapeListenerAdded = true;
        }
    }

    openSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        if (sidebar && sidebarOverlay) {
            sidebar.classList.add('open');
            sidebarOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        if (sidebar && sidebarOverlay) {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    loadGenres() {
        const genresContainer = document.getElementById('sidebarGenres');
        if (!genresContainer) {
            return;
        }

        // Hacer petición AJAX para obtener los géneros
        fetch('../../app/controller/getGenres.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.genres) {
                    this.renderGenres(data.genres);
                } else {
                    this.showGenresError();
                }
            })
            .catch(error => {
                console.error('Error cargando géneros:', error);
                this.showGenresError();
            });
    }

    renderGenres(genres) {
        const genresContainer = document.getElementById('sidebarGenres');
        if (!genresContainer) return;

        genresContainer.innerHTML = '';
        
        genres.forEach(genre => {
            const link = document.createElement('a');
            link.href = `home.php?genero=${genre.Id_Genero}`;
            link.className = 'sidebar-link';
            link.innerHTML = `
                <i class="fa fa-tag"></i>
                ${genre.Genero}
            `;
            genresContainer.appendChild(link);
        });
    }

    showGenresError() {
        const genresContainer = document.getElementById('sidebarGenres');
        if (!genresContainer) return;

        genresContainer.innerHTML = '<div class="loading-text">Error cargando géneros</div>';
    }

    updateThemeToggle() {
        // Usar querySelector para obtener solo el primer elemento (evitar duplicados)
        const themeToggle = document.querySelector('#sidebar-theme-toggle');
        if (!themeToggle) return;
        
        const themeIcon = themeToggle.querySelector('#theme-icon');
        const themeText = themeToggle.querySelector('#theme-text');
        
        if (!themeIcon || !themeText) return;
        
        const currentTheme = localStorage.getItem('theme') || 'dark';
        
        if (currentTheme === 'dark') {
            themeIcon.className = 'fa fa-moon-o';
            themeText.textContent = 'Modo Oscuro';
        } else {
            themeIcon.className = 'fa fa-sun-o';
            themeText.textContent = 'Modo Claro';
        }
    }
}

// Función global para cambiar tema desde el sidebar
function toggleThemeFromSidebar() {
    if (window.themeSwitcher) {
        window.themeSwitcher.toggleTheme();
        // Actualizar el icono y texto en el sidebar
        setTimeout(() => {
            if (window.sidebarManager) {
                window.sidebarManager.updateThemeToggle();
            }
        }, 100);
    }
}

// Escuchar cambios de tema para actualizar el sidebar
window.addEventListener('themeChanged', () => {
    if (window.sidebarManager) {
        window.sidebarManager.updateThemeToggle();
    }
});

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.sidebarManager = new SidebarManager();
});

// También inicializar si se carga después del DOM
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.sidebarManager = new SidebarManager();
    });
} else {
    window.sidebarManager = new SidebarManager();
}

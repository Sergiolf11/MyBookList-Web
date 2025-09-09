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
            });
        } else {
            this.setupSidebar();
            this.observeDOMChanges();
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
}

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

import gsap from 'gsap';

export default (id, activeTab = null) => {
    return {
        activeTab,
        id,
        init() {
            const $activeTab = this.$root.querySelector('[aria-selected=true]');
            const hash = window.location.hash.substring(1).trim();

            if ($activeTab) {
                this.activeTab = $activeTab.id.split('-')[1];
            }

            if (hash !== '') {
                const params = hash.split(',');
                const component = params.find((param) => param.startsWith(this.id));

                if (component) {
                    this.activeTab = component.split(':')[1];
                }
            }
        },
        setActiveTab(tab) {
            const heightCurrent = this.$refs.panels.offsetHeight;

            // Clip container
            this.$refs.panels.style.height = `${heightCurrent}px`;
            this.$refs.panels.style.overflow = 'hidden';
            this.$refs.panels.offsetHeight;

            // Set new tab
            this.activeTab = tab;

            this.$nextTick(() => {
                const heightNew = document.getElementById(`tabpanel-${tab}`).offsetHeight;

                if (heightCurrent === heightNew) {
                    this.$refs.panels.style.height = '';
                    this.$refs.panels.style.overflow = '';
                    return;
                }

                gsap.to(this.$refs.panels, {
                    duration: 0.5,
                    ease: 'power3.out',
                    height: heightNew,
                    onComplete: () => {
                        this.$refs.panels.style.height = '';
                        this.$refs.panels.style.overflow = '';
                    },
                });
            });

            // Update URL hash
            window.location.hash = `${this.id}:${tab}`;
            const hash = window.location.hash.substring(1).trim();
            const params = hash.split(',').filter((param) => !param.startsWith(this.id));
            params.push(`${this.id}:${tab}`);
            params.sort();
            window.location.hash = params.join(',');
        },
    };
};

document.addEventListener('DOMContentLoaded', () => {
    const blocksArea = document.getElementById('blocks-area');
    const addSelect = document.getElementById('add-section-select');
    const saveLayoutBtn = document.getElementById('save-layout');

    const projectId = blocksArea.dataset.projectId;

    // Make a block editable
    const makeEditable = (block) => {
        block.querySelectorAll('[contenteditable]').forEach(el => {
            el.addEventListener('focus', () => el.style.background = '#fff9c4');
            el.addEventListener('blur', () => el.style.background = '');
        });
    };

    // Initialize Draggable on blocks
    const updateDraggable = () => {
        const blocks = document.querySelectorAll('#blocks-area .block');
        blocks.forEach(block => {
            makeEditable(block);

            if (block._draggable) block._draggable.kill();

            const draggable = Draggable.create(block, {
                type: "y",
                bounds: blocksArea,
                edgeResistance: 0.65,
                inertia: true,
                onDrag: function () {
                    const dragged = this.target;
                    blocks.forEach(target => {
                        if (target !== dragged) {
                            const rect = target.getBoundingClientRect();
                            const dragRect = dragged.getBoundingClientRect();
                            const middle = rect.top + rect.height / 2;

                            if (dragRect.top < middle && dragRect.bottom > rect.top) {
                                blocksArea.insertBefore(dragged, target);
                            } else if (dragRect.bottom > middle && dragRect.top < rect.bottom) {
                                blocksArea.insertBefore(dragged, target.nextSibling);
                            }
                        }
                    });
                },
                onDragEnd: function () { gsap.to(this.target, { y: 0, duration: 0.2 }); }
            })[0];

            block._draggable = draggable;
        });
    };

    // Fetch section HTML from server
    const fetchSection = async (template, section) => {
        try {
            const res = await fetch(`index.php?page=render_section&template=${template}&section=${section}`);
            if (!res.ok) throw new Error('Failed to fetch section');
            return await res.text();
        } catch (err) {
            console.error(err);
            return '';
        }
    };

    // Add new section via select dropdown
    addSelect.addEventListener('change', async e => {
        const type = e.target.value;
        if (!type) return;

        // Default template for now
        const template = 'minimalist';
        const sectionFile = `${type}.php`;

        const sectionHTML = await fetchSection(template, sectionFile);
        if (sectionHTML) {
            const block = document.createElement('div');
            block.classList.add('block');
            block.dataset.type = type;
            block.innerHTML = sectionHTML;
            blocksArea.appendChild(block);
            updateDraggable();
        }

        e.target.value = '';
    });

    // Drag templates from sidebar
    document.querySelectorAll('.template-item').forEach(item => {
        item.addEventListener('dragstart', e => e.dataTransfer.setData('template', item.dataset.template));
    });

    blocksArea.addEventListener('dragover', e => e.preventDefault());
    blocksArea.addEventListener('drop', async e => {
        e.preventDefault();
        const templateName = e.dataTransfer.getData('template');
        if (!templateName) return;

        // Fetch all sections of that template
        const sections = ['hero', 'features', 'cta']; // optionally fetch dynamically via AJAX
        for (let section of sections) {
            const html = await fetchSection(templateName, section + '.php');
            if (html) {
                const block = document.createElement('div');
                block.classList.add('block');
                block.dataset.type = section;
                block.innerHTML = html;
                blocksArea.appendChild(block);
                updateDraggable();
            }
        }
    });

    // Save layout
    saveLayoutBtn.addEventListener('click', () => {
        const blocks = document.querySelectorAll('#blocks-area .block');
        const layout = Array.from(blocks).map(block => {
            const type = block.dataset.type;
            let content = {};

            if (type === 'hero') {
                const h2 = block.querySelector('h2');
                const p = block.querySelector('p');
                content = { heading: h2?.textContent || '', subheading: p?.textContent || '' };
            } else if (type === 'features') {
                content = Array.from(block.querySelectorAll('h3')).map((h, i) => ({
                    title: h.textContent,
                    desc: block.querySelectorAll('p')[i]?.textContent || ''
                }));
            } else if (type === 'cta') {
                const a = block.querySelector('a');
                content = { text: a?.textContent || '', link: a?.getAttribute('href') || '#' };
            } else {
                content = { html: block.innerHTML };
            }

            return { type, content };
        });

        fetch('index.php?page=save_layout', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ project_id: projectId, layout })
        })
            .then(res => res.json())
            .then(data => alert(data.message))
            .catch(err => console.error(err));
    });
});

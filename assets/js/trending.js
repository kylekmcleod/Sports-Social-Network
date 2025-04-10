sfunction updateTrendingTags() {
    fetch('../src/controllers/trendingController.php?ajax=1')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(trending => {
            if (trending.error) {
                console.error('Error fetching trending tags:', trending.error);
                return;
            }

            const container = document.querySelector('.trends-for-you');
            if (!container) return;

            // Keep the header
            const header = container.querySelector('.trends-for-you__block:first-child');
            container.innerHTML = '';
            container.appendChild(header);

            trending.forEach((tag, index) => {
                const block = document.createElement('div');
                block.className = 'trends-for-you__block';
                
                block.innerHTML = `
                    <div class="trends-for-you__meta-information">
                        ${index === 0 ? 'Most Popular' : `#${index + 1} Trending`}
                    </div>
                    <div class="trends-for-you__trend-name">
                        #${tag.tag}
                    </div>
                    <div class="trends-for-you__meta-information">
                        ${tag.count} posts
                    </div>
                `;
                
                block.addEventListener('click', () => {
                    window.location.href = `explorepage.php?tag=${encodeURIComponent(tag.tag)}`;
                });
                
                container.appendChild(block);
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Update trending tags immediately and then every minute
document.addEventListener('DOMContentLoaded', () => {
    updateTrendingTags();
    setInterval(updateTrendingTags, 60000);
});

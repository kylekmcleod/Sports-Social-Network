function updateTrendingTags() {
    fetch('../src/controllers/trendingController.php?ajax=1')
        .then(response => response.json())
        .then(trending => {
            const container = document.querySelector('.trends-for-you');
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
                container.appendChild(block);
            });
        })
        .catch(error => console.error('Error fetching trending tags:', error));
}

document.addEventListener('DOMContentLoaded', () => {
    updateTrendingTags();
    setInterval(updateTrendingTags, 60000);
});

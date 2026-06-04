    <!-- Testimonial Section Started -->
    <section class="testimonial-section py-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 mt-3">
                    <div class="testimonial-title">
                        <h2>Testimonials </h2>
                        <p>At Sh Binayak Hospital, every patient story inspires us to deliver better care each day.
                            We take pride in providing compassionate treatment with advanced medical facilities.
                            Our dedicated team ensures a seamless journey from diagnosis to recovery.
                            These heartfelt testimonials reflect the trust and confidence patients place in us</p>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12 mt-3">
                    <!-- Elfsight Google Reviews | Untitled Google Reviews -->
                    <script src="https://elfsightcdn.com/platform.js" async></script>
                    <div class="elfsight-app-ac1ce727-2e03-4a70-8444-65523009f02d" data-elfsight-app-lazy style="all:unset;"></div>
                    <!-- Optional: Add a direct link for users to leave a review -->
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Section Ends -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const target = document.querySelector('.elfsight-app-ac1ce727-2e03-4a70-8444-65523009f02d');
    const observer = new MutationObserver(() => {
        const links = target.querySelectorAll('a');
        links.forEach(a => a.style.display = 'none');
    });
    observer.observe(target, { childList: true, subtree: true });
});
</script>

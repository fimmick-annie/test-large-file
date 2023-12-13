AnimatedBtn = (options) => {
    const domId = options.domId;
    const domClass = options.domClass;
    const transitionTime = options.transitionTime;
    const yourFunction = options.yourFunction;
    const coverColor = options.coverColor;
    const baseColor = options.baseColor;
    let done;
    let yourInterval;
    if (domId) {
        let dom = document.querySelector(`${domId}`) || document.querySelector(`#${domId}`);
        if(dom) {
            dom.style.background = `linear-gradient(to right, ${coverColor} 50%, ${baseColor} 50%)`;
            dom.style.backgroundSize = '200% 100%';
            dom.style.backgroundPosition = 'right bottom';
            dom.style.cursor = 'pointer';
            dom.addEventListener('mousedown', (e) => {
                done = false;
                dom.style.transition = `all ${transitionTime / 1000}s` || 'all 1s';
                yourInterval = setInterval(() => {
                    yourFunction();
                    done = true;
                    clearInterval(yourInterval);
                }, transitionTime);
                dom.style.backgroundPosition = 'left bottom';
            })
            dom.addEventListener('mouseup', (e) => {
                if (done) {
                    dom.style.transition = '';
                }
                clearInterval(yourInterval);
                dom.style.backgroundPosition = 'right bottom';
            });
        }
    } else if(domClass ) {
        let domArr = document.querySelectorAll(`.${domClass}`).length ? document.querySelectorAll(`.${domClass}`) : document.querySelectorAll(`${domClass}`);
        if(domArr) {
            domArr.forEach(dom => {
                dom.style.background = `linear-gradient(to right, ${coverColor} 50%, ${baseColor} 50%)`;
                dom.style.backgroundSize = '200% 100%';
                dom.style.backgroundPosition = 'right bottom';
                dom.style.cursor = 'pointer';
                dom.addEventListener('mousedown', (e) => {
                    done = false;
                    dom.style.transition = `all ${transitionTime / 1000}s` || 'all 1s';
                    yourInterval = setInterval(() => {
                        yourFunction(e);
                        done = true;
                        clearInterval(yourInterval);
                    }, transitionTime);
                    dom.style.backgroundPosition = 'left bottom';
                })
                dom.addEventListener('mouseup', (e) => {
                    if (done) {
                        dom.style.transition = '';
                    }
                    clearInterval(yourInterval);
                    dom.style.backgroundPosition = 'right bottom';
                });
            })
        }
    }

}

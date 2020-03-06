import './menu.scss';

const menuClass = 'menu';
const menuListClass = `${menuClass}__list`;

function init(){

    const menuItems = $(`.${menuListClass}>li`);
    const itemsCount = menuItems.length-1;

    if ( itemsCount>1 ){
        const counts = {
            left: 0,
            right: 0
        };
        if ( itemsCount%2 === 1 ){
            counts.left = Math.trunc(itemsCount/2)+1;
            counts.right = Math.trunc(itemsCount/2);
        } else {
            counts.left = Math.trunc(itemsCount/2);
            counts.right = counts.left;
        }
        for (let itemIndex = (itemsCount-counts.right); itemIndex <= itemsCount; itemIndex++){
            menuItems.eq(itemIndex).addClass('menu__item_right');
        }
    }
}

export default init;
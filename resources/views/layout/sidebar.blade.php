<style>
    .left-sidebar-menu {
        margin: 0px 10px 50px 0px;
        min-height: auto;

        -ms-flex: 0 0 250px;
        flex: 0 0 250px;
        /*background-color: #FFF;*/
        /*background-image: linear-gradient(#fff, #FAF9FD);*/

        background: #ffffff; /* Old browsers */
        background: -moz-linear-gradient(top, #ffffff 70%, #faf9fd 100%, #faf9fd 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(top, #ffffff 70%, #faf9fd 100%, #faf9fd 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(to bottom, #ffffff 70%, #faf9fd 100%, #faf9fd 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#faf9fd', GradientType=0);

        border-radius: 5px 5px 0px 0px;
        /*box-shadow: 2px 0px 1px rgba(0, 0, 0, 0.1);
        -moz-box-shadow: 2px 0px 1px rgba(0, 0, 0, 0.03);
        -webkit-box-shadow: 2px 0px 1px rgba(0, 0, 0, 0.03);
        -o-box-shadow: 2px 0px 1px rgba(0, 0, 0, 0.03);*/
    }

    @media (max-width: 690px) {
        .left-sidebar-menu {
            display: none;
        }
        #left-sidebar-mobile {
            display: block;
        }
    }

    @media (min-width: 691px) {
        #left-sidebar-mobile {
            display: none;
        }
    }

    #sidebar-wrapper {
        /*min-height: 100vh;*/
        -webkit-transition: margin .25s ease-out;
        -moz-transition: margin .25s ease-out;
        -o-transition: margin .25s ease-out;
        transition: margin .25s ease-out;
    }

    #sidebar-wrapper .sidebar-heading {
        padding: 20px 0px 10px 0px;
        /*padding: 0.875rem 1.25rem;*/
        font-size: 1.2rem;
    }

    #sidebar-wrapper .list-group {
        /*width: 15rem;*/
    }

    #sidebar-wrapper .list-group .list-group-item:first-child {
        border-top: 0px;
        border-bottom: 1px solid #ebedf2;
    }

    #sidebar-wrapper .list-group .list-group-item {
        border-bottom: 1px solid #ebedf2;
    }

    #sidebar-wrapper .list-group-item-action:hover {
        color: #ff0000;
        background: inherit;
        /*width: 15rem;*/
    }

    #sidebar-wrapper .list-group-item.active {
        color: #ff0000;
        background: inherit;
        border: inherit;
        /*background: #ff0000;(/
        /*width: 15rem;*/
    }

    #wrapper.toggled #sidebar-wrapper {
        margin-left: 0;
    }
</style>
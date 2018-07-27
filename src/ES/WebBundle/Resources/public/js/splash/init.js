$("#grid").mason({
    itemSelector: ".box",
    ratio: 1.5,
    sizes: [
        [1, 1],
        [1, 2],
        [2, 2],
    ],
    columns: [
        [0, 110, 1],
        [110, 220, 2],
        [220, 440, 4],
    ],
    filler: {
        itemSelector: '.fillerBox',
        filler_class: 'custom_filler'
    },
    layout: 'fluid',
}, function() {
    console.log("COMPLETE!")
});
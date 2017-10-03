import React, { Component } from 'react';
import ReactDOM from 'react-dom';

class SearchResultComponent extends Component {
    constructor(props) {
        super(props);
        this.keyEvents = this.keyEvents.bind(this);
        this.state = {
            selected: null,
            closed: false,
        }
    }

    componentDidMount() {
        document.addEventListener('keyup', this.keyEvents);
    }

    componentWillUnmount() {
        document.removeEventListener('keyup', this.keyEvents);
    }

    componentDidUpdate(prevProps, prevState) {
        if (this.props.data !== prevProps.data) {
            this.setState({
                selected: null,
                closed: false
            });
        }
    }

    keyEvents(event) {
        if (event.keyCode === 38) {
            this.updateSelectedResult('up');
        } else if (event.keyCode === 40) {
            this.updateSelectedResult('down');
        } else if (event.keyCode === 13) {
            this.onEnterSelect();
        } else if (event.keyCode === 27 && this.state.closed === false) {
            this.setState({closed: true});
        } else {
            return;
        }

        event.preventDefault();
    }

    updateSelectedResult(direction) {
        let currentIndex = this.getCurrentIndex();
        let navigatedIndex = this.getNavigatedIndex(currentIndex, direction);
        let navigatedResult = this.props.data[navigatedIndex];

        this.setSelectedResult(navigatedResult);
    }

    getCurrentIndex() {
        if (this.state.selected) {
            let selected = this.state.selected;
            return _(this.props.data).findIndex((searchResult) => {
                return searchResult.display_name === selected.display_name;
            });
        }

        return null;
    }

    getNavigatedIndex(currentIndex, direction) {
        let maxIndex = this.props.data.length - 1;

        if (currentIndex === null) {
            if (direction === 'down') {
                currentIndex = 0;
            } else if (direction === 'up')  {
                currentIndex = maxIndex;
            }
        } else {
            if (direction === 'up') {
                currentIndex--;
            } else if (direction === 'down') {
                currentIndex++;
            }

            if (currentIndex < 0) {
                // If navigated above first result in list, set selected to last
                currentIndex = maxIndex;
            } else if (currentIndex > maxIndex) {
                // If navigated below last result in list, set selected to first
                currentIndex = 0;
            }
        }

        return currentIndex;
    }

    setSelectedResult(place) {
        this.setState({selected: place});
    }

    onEnterSelect() {
        if (this.state.selected) {
            // Use selected
            this.onSelect(this.state.selected);
        } else if (this.props.data && this.props.data.length > 0) {
            // If nothing selected, use first
            this.onSelect(this.props.data[0]);
        }
    }

    onSelect(place) {
        if (typeof this.props.onSelect === 'function') {
            this.props.onSelect(place);
        }
    }

    resultList() {
        let that = this;
        let selectedIndex = this.getCurrentIndex();

        return _(this.props.data).map((place, index) => {
            let parts = place.display_name.split(',');
            let selected = selectedIndex === index ? 'selected' : '';

            return (
                <li key={index} className={selected} onClick={that.onSelect.bind(this, place)}>
                    <b>{_(parts).first()}</b>
                    <p>{_(parts).rest(1).join(', ')}</p>
                </li>
            );
        });
    }

    render() {
        if (!this.props.data || this.props.data.length === 0 || this.state.closed === true) {
            return null;
        }

        let results = this.resultList();

        return (
            <ul className='search-results'>
                {results}
            </ul>
        );
    }
}

module.exports = SearchResultComponent;

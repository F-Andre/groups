import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSpinner } from '@fortawesome/free-solid-svg-icons';

export default class LoadModal extends Component {
    render() {
        return (
          <div className="modal fade" id="loadModalDiv" aria-labelledby="loadModalLabel" aria-hidden="true">
            <div className="modal-dialog-centered" role="document">
              <div className="modal-content">
                <div className="modal-header">
                  <h5 className="modal-title" id="loadModalLabel">Traitement en cours</h5>
                </div>
                <div className="modal-body text-center">
                  <i className="fas fa-spinner fa-pulse"></i>
                </div>
              </div>
            </div>
          </div>
        );
    }
}
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import LoadModal from './loadModal';

function Name(props) {
    return (
        <input
            type="text"
            className="form-control col-6"
            name="name"
            id="name"
            value={props.value}
            onChange={props.onChange}
        />
    );
}

function Email(props) {
    return (
        <input
            type="email"
            className="form-control col-6"
            name="email"
            id="email"
            value={props.value}
            onChange={props.onChange}
        />
    );
}

export default class UserEditForm extends Component {
    constructor(props) {
        super(props)
        this.state = {
            nameValue: name,
            emailValue: email,
            imgSrc: avatar,
            imgSize: 0,
            modified: false,
            avatarDeleted: '',
        }
        this.handleChangeName = this.handleChangeName.bind(this);
        this.handleChangeEmail = this.handleChangeEmail.bind(this);
        this.handleChangeAvatar = this.handleChangeAvatar.bind(this);
        this.handleDeleteAvatar = this.handleDeleteAvatar.bind(this);
        this.fileInput = React.createRef();
    }

    handleChangeName(event) {
        this.setState({
            nameValue: event.target.value,
            modified: true
        })
    }

    handleChangeEmail(event) {
        this.setState({
            emailValue: event.target.value,
            modified: true
        })
    }

    handleChangeAvatar() {
        const reader = new FileReader();
        let file = this.fileInput.current.files[0];
        let fileSrc = '', fileSize = file.size;
        reader.onload = (e) => {
          fileSrc = e.target.result;
          this.setState({
              imgSrc: fileSrc,
              imgSize: fileSize,
              modified: true
            });
        };
        reader.readAsDataURL(file);
    }

    handleDeleteAvatar() {
        this.setState({
            imgSrc: defaultAvatar,
            modified: true,
            imageDeleted: true,
        })
    }

    render() {
        const imageSizeMax = 4718592
        const disabledState = !this.state.modified ? true : this.state.imgSize > imageSizeMax ? true : false
        const submitClass = !disabledState ? "btn btn-primary" : "btn btn-secondary disabled"
        const disableDelete = this.state.imgSrc != '/storage/default/default_avatar.png' ? "btn btn-danger btn-sm" : "btn btn-danger btn-sm disabled"
        const aStyle = {
            backgroundImage: 'url(' + this.state.imgSrc + ')',
          };
        const imageClass = this.state.imgSize > imageSizeMax ? 'form-control is-invalid' : 'form-control'

        return (
            <div>
                <div className="form-group">
                    <label htmlFor="titre">Nom:</label>
                    <Name value={this.state.nameValue} onChange={this.handleChangeName} />
                </div>
                <div className="form-group">
                    <label htmlFor="contenu">Adresse e-mail:</label>
                    <Email value={this.state.emailValue} onChange={this.handleChangeEmail} />
                </div>
                <div id="divAvatar" className="form-group">
                    <p>Avatar:</p>
                    <label id="avatarLabel" htmlFor="avatar" className="btn btn-outline-secondary m-0 avatar" style={aStyle}></label>
                    <input id="avatar" name="avatar" className={imageClass} type="file" accept=".JPG, .PNG, .GIF" ref={this.fileInput} onChange={this.handleChangeAvatar} />
                    <div className="invalid-feedback">L'image doit être aux formats jpg, png ou gif et avoir une taille max de 4.5Mo.</div>
                    <div className="form-group mt-4">
                        <a id="btnDeleteAvatar" className={disableDelete} onClick={this.handleDeleteAvatar}>Effacer l'image</a>
                    </div>
                    <input id="avatarDeleted" type="text" className="disabled" name="avatarDeleted" value={this.state.imageDeleted} />
                </div>
                <LoadModal />
                <button type="submit" data-toggle="modal" data-target="#loadModalDiv" className={submitClass} disabled={disabledState}>Modifier</button>
            </div>
        )
    }
}

if (document.getElementById('userEditForm')) {
    ReactDOM.render(<UserEditForm />, document.getElementById('userEditForm'));
}
